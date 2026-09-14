<?php

class pluginSettingsPlus extends Plugin
{
    private $pluginName;
    private $cssFile;
    private $uploadsDir;
    private $siteFaviconPath = '';

    public function init()
    {
        // プラグイン名
        $this->pluginName =  substr(__CLASS__, 6); //先頭のpluginを除いたクラス名

        // プラグインフォルダ
        $this->cssFile = $this->domainPath() . 'css/SettingsPlus.css';

        // Bludit の標準アップロードディレクトリ
        $this->uploadsDir = PATH_UPLOADS . $this->pluginName . DS;
        if (!is_dir($this->uploadsDir)) {
            @mkdir($this->uploadsDir, 0755, true);
        }

        // サイトのファビコンパスを初期化
        $this->siteFaviconPath = HTML_PATH_UPLOADS . $this->pluginName . '/';

        // データベースフィールドの初期値を設定
        $this->dbFields = array(
            'displaySiteTitle' => true,
            'faviconAdmin' => '',
        );
    }

    /* ---------------------------------------------------------
     * 管理画面フォーム
     * --------------------------------------------------------- */
    public function form()
    {
        global $page;
        // 管理画面フォームの HTML を生成
		$html = '<div class="alert alert-primary" role="alert">';
		$html .= $this->description();
		$html .= '</div>';

        // サイトタイトル表示設定
		$html .= '<div>';
		$html .= '<label>' . 'Display site title in the admin sidebar' . '</label>';
		$html .= '<select name="displaySiteTitle">';
		$html .= '<option value="true" ' . ($this->getValue('displaySiteTitle') === true ? 'selected' : '') . '>Enabled</option>';
		$html .= '<option value="false" ' . ($this->getValue('displaySiteTitle') === false ? 'selected' : '') . '>Disabled</option>';
		$html .= '</select>';
		$html .= '</div>';

        // adminのファビコンにサイトのファビコンを適用させる
		$html .= '<div>';
		$html .= '<label>' . 'Favicon in the admin' . '</label>';
		$html .= '<input name="faviconAdminFile" id="jsfaviconAdminFile" type="file" accept="image/x-icon,image/png,image/jpeg">';
		$html .= '<small>' . 'Upload ICO, PNG, or JPG file' . '</small>';
		if ($this->getValue('faviconAdmin')) {
			$html .= '<div style="margin-top: 10px;">';
			$html .= '<strong>Current:</strong> ' . htmlspecialchars(basename($this->getValue('faviconAdmin')));
			$html .= '</div>';
		}
		$html .= '</div>';

        // デバッグ要素として実際に保存された値を表示
        $html .= '<h6 class="mt-4 mb-2 border-bottom">Actual saved value (debug)</h6>';
        $html .= '<div class="card-body card metric-card" style="display:block;">';
        $html .= '<pre>';
        $html .= 'displaySiteTitle value: ' . var_export($this->getValue('displaySiteTitle'), true) . "(" . gettype($this->getValue('displaySiteTitle')) . ")" . "\n";
        $html .= 'faviconAdmin value: ' . var_export($this->getValue('faviconAdmin'), true) . "(" . gettype($this->getValue('faviconAdmin')) . ")" . "\n";
        $html .= '</pre>';
        $html .= '</div>';

        return $html;
    }

    /* ---------------------------------------------------------
     * 管理画面 POST
     * --------------------------------------------------------- */
    public function post()
    {
        // ファイルアップロード処理
        if (isset($_FILES['faviconAdminFile']) && $_FILES['faviconAdminFile']['error'] === UPLOAD_ERR_OK) {
            $uploadDir = PATH_UPLOADS . $this->pluginName . DS;
            if (!is_dir($uploadDir)) {
                @mkdir($uploadDir, 0755, true);
            }

            $file = $_FILES['faviconAdminFile'];
            $allowedTypes = ['image/x-icon', 'image/png', 'image/jpeg'];
            $allowedExtensions = ['ico', 'png', 'jpg', 'jpeg'];

            // ファイルタイプチェック
            if (!in_array($file['type'], $allowedTypes)) {
                return;
            }

            // ファイル拡張子チェック
            $ext = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
            if (!in_array($ext, $allowedExtensions)) {
                return;
            }

            // 新しいファイル名を生成
            $newFilename = 'favicon-' . time() . '.' . $ext;
            $filepath = $uploadDir . $newFilename;

            // ファイルを移動
            if (move_uploaded_file($file['tmp_name'], $filepath)) {
                // 古いファイルを削除
                if ($this->getValue('faviconAdmin')) {
                    $oldPath = $uploadDir . basename($this->getValue('faviconAdmin'));
                    if (file_exists($oldPath)) {
                        @unlink($oldPath);
                    }
                }

                // パスをデータベースに保存（相対パス）
                $this->faviconAdmin = HTML_PATH_UPLOADS . $this->pluginName . '/' . $newFilename;
            }
        }
    }

    /* ---------------------------------------------------------
     * Admin ページの HTML を拡張
     * --------------------------------------------------------- */
    public function adminHead()
    {
        global $page;

        // CSS ファイルを読み込む（絶対パスで指定）
        $cssUrl = $this->domainPath() . 'css/SettingsPlus.css';
        echo '<link rel="stylesheet" href="' . $cssUrl . '">';

        // Favicon を設定
        if ($this->getValue('faviconAdmin')) {
            echo '<link rel="icon" href="' . $this->getValue('faviconAdmin') . '">';
        }
    }
    public function adminBodyBegin()
    {
    }

    public function adminBodyEnd()
    {
        if (!$this->getValue('displaySiteTitle')) {
            return;
        }
        // サイトタイトルを取得して返す
        global $site;
        $title = $site->title();

        $html = '<script>
            $(document).ready(function() {
                // BLUDIT テキストを含む li を探す
                var bluditLi = $(".nav-item").filter(function() {
                    return $(this).text().includes("BLUDIT");
                });
                
                if (bluditLi.length > 0) {
                    // 新しい li 要素を作成
                    var newLi = $("<li>").addClass("nav-item")
                        .append(
                            $("<span>").addClass("nav-link")
                            .text("' . htmlspecialchars($title) . '")
                        );
                    
                    // BLUDIT の次に挿入
                    bluditLi.after(newLi);
                }
            });
        </script>';
        
        return $html;
    }

    public function adminSidebar()
    {
        global $page;

        $admin_dir = ADMIN_URI_FILTER;
        $pluginName = (__CLASS__);// ここはクラス名そのまま
        $url = HTML_PATH_ADMIN_ROOT.'configure-plugin/'.$pluginName;
        // サイドバーに「Settings Plus」リンクを追加
        $html = '<li class="nav-item">
            <a class="nav-link" href="' . $url . '"><span class="fa fa-gears"></span>Settings Plus</a>
        </li>';
        return $html;

    }



    /* ---------------------------------------------------------
     * Utility
     * --------------------------------------------------------- */
    private function uuid()
    {
        return bin2hex(random_bytes(16));
    }

}
