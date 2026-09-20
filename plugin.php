<?php

class pluginSettingsPlus extends Plugin
{
    private $pluginName;
    private $cssFile;
    private $uploadsDir;
    private $faviconAdminPath = '';

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

        // adminのファビコンパスを初期化
        $this->faviconAdminPath = HTML_PATH_UPLOADS . $this->pluginName . '/';

        // データベースフィールドの初期値を設定
        $this->dbFields = array(
            'displaySiteTitle' => true,
            'faviconAdmin' => '',
            'enableFaviconAdmin' => false,
            'useFontAwesome' => false,
            'awesomeURL' => 'https://cdnjs.cloudflare.com/ajax/libs/font-awesome/7.3.1/css/all.min.css'
        );
    }

    /* ---------------------------------------------------------
     * 管理画面フォーム
     * --------------------------------------------------------- */
    public function form()
    {
        global $page;
        global $layout;

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
        $html .= '<div class="card-body card metric-card" style="display:block;">';
		$html .= '<select name="enableFaviconAdmin">';
		$html .= '<option value="true" ' . ($this->getValue('enableFaviconAdmin') === true ? 'selected' : '') . '>Enabled</option>';
		$html .= '<option value="false" ' . ($this->getValue('enableFaviconAdmin') === false ? 'selected' : '') . '>Disabled</option>';
		$html .= '</select>';
		$html .= '<input id="faviconAdminFile" name="faviconAdminFile" type="file" accept="image/png" style="display:none;">';
        $html .= '<div style="display: flex; align-items: center; gap: 10px;">';
        $html .= '<img id="faviconAdminFilename" src="' . $this->getValue('faviconAdmin') . '" alt="Favicon" style="max-width:64px; max-height:64px;">';
		$html .= '<label for="faviconAdminFile" class="btn btn-sm btn-outline-secondary">Upload favicon</label>';
		$html .= '</div>';
        $html .= '<small>Need to clear cache after uploading a new favicon.</small>';
		$html .= '</div>';
		$html .= '</div>';

        // Font Awesome 使用設定
        $html .= '<div>';
        $html .= '<label>' . 'Use Font Awesome' . '</label>';
        $html .= '<div class="card-body card metric-card" style="display:block;">';
        $html .= '<select name="useFontAwesome">';
        $html .= '<option value="true" ' . ($this->getValue('useFontAwesome') === true ? 'selected' : '') . '>Enabled</option>';
        $html .= '<option value="false" ' . ($this->getValue('useFontAwesome') === false ? 'selected' : '') . '>Disabled</option>';
        $html .= '</select>';
        $html .= '<div style="margin-top: 1em;">';
        $html .= '<input type="text" name="awesomeURL" value="' . $this->getValue('awesomeURL') . '" placeholder="Font Awesome URL">';
        $html .= '<small>Specify the URL for the Font Awesome CSS file.</small>';
        $html .= '</div>';
        $html .= '<hr>';
        $html .= '<div  style="display: flex; align-items: center; gap: 1em;">When successfully loaded, display house icon:<i class="fa-regular fa-house fa-2x"></i></i></div>';
        $html .= '</div>';
        $html .= '</div>';

        // ファイル選択後に自動送信
        $html .= '<script>';
        $html .= 'document.getElementById("faviconAdminFile").addEventListener("change", function() {';
        $html .= '  var form = document.querySelector("form");';
        $html .= '  if (form) form.submit();';
        $html .= '});';
        $html .= '</script>';

        // フォーム全体に enctype を設定
        $html .= '<script>';
        $html .= 'document.addEventListener("DOMContentLoaded", function() {';
        $html .= '  var form = document.querySelector("form");';
        $html .= '  if (form) form.setAttribute("enctype", "multipart/form-data");';
        $html .= '});';
        $html .= '</script>';

        // デバッグ要素として実際に保存された値を表示
        $html .= '<div id="accordion-basic">
            <label><a data-toggle="collapse" href="#dbg">Actual saved value (debug)<i class="fa fa-chevron-down"></i></a></label>
            <div class="card-body card metric-card" style="display:block;">
            <div id="dbg" class="collapse">';
        $html .= '<pre>' .
            'displaySiteTitle: ' . var_export($this->getValue('displaySiteTitle'), true) . "(" . gettype($this->getValue('displaySiteTitle')) . ")" . "\n" .
            'enableFaviconAdmin: ' . var_export($this->getValue('enableFaviconAdmin'), true) . "(" . gettype($this->getValue('enableFaviconAdmin')) . ")" . "\n" .
            'faviconAdmin: ' . var_export($this->getValue('faviconAdmin'), true) . "(" . gettype($this->getValue('faviconAdmin')) . ")" . "\n" .
            'useFontAwesome: ' . var_export($this->getValue('useFontAwesome'), true) . "(" . gettype($this->getValue('useFontAwesome')) . ")" . "\n" .
            'awesomeURL: ' . var_export($this->getValue('awesomeURL'), true) . "(" . gettype($this->getValue('awesomeURL')) . ")" . "\n" .
            'layout: ' . var_export($layout, true) . "(" . gettype($layout) . ")" . "\n" .
            '</pre>';
        $html .='</div>
            </div>
            </div>';

        return $html;
    }

    /* ---------------------------------------------------------
     * 管理画面 POST
     * --------------------------------------------------------- */
    public function post()
    {
        // 親クラスの post() を呼び出して自動マッピングを実行
        // これにより displaySiteTitle などが自動的にDBに保存される
        parent::post();

        // 追加処理が必要な場合は以下に記述する
        $file = $_FILES['faviconAdminFile'] ?? null;
        
        // ファイルアップロード処理
        if (isset($file)) {
            $uploadDir = PATH_UPLOADS . $this->pluginName . DS;
            if (!is_dir($uploadDir)) {
                @mkdir($uploadDir, 0755, true);
            }

            $allowedTypes = ['image/png'];
            $allowedExtensions = ['png'];

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
            $newFilename = 'faviconAdmin.' . $ext;
            $filepath = $uploadDir . $newFilename;
            // パスをデータベースに保存（相対パス）
            $this->db['faviconAdmin'] = HTML_PATH_UPLOADS . $this->pluginName . '/' . $newFilename;

            // ファイルを移動
            if (move_uploaded_file($file['tmp_name'], $filepath)) {
                // 古いファイルを削除
                $oldFaviconPath = $this->getValue('faviconAdmin');
                if (!empty($oldFaviconPath)) {
                    // getValue() が返すのは URL パス（例：uploads/SettingsPlus/favicon.ico）
                    // 実際のファイルパスは PATH_UPLOADS + SettingsPlus + ファイル名
                    $oldFilename = basename($oldFaviconPath);
                    $oldFilepath = $uploadDir . $oldFilename;
                    if (file_exists($oldFilepath) && $oldFilepath !== $filepath) {
                        @unlink($oldFilepath);
                    }
                }
            }
            else {
                // ファイル移動失敗時は新しいパス設定を取り消す
                $this->db['faviconAdmin'] = '';
            }
        }
        return $this->save();
    }

    /* ---------------------------------------------------------
     * Admin ページの HTML を拡張
     * --------------------------------------------------------- */
    public function siteHead()
    {
        // Font Awesome の読み込み
        if ($this->getValue('useFontAwesome')) {
            echo '<link rel="stylesheet" href="' . $this->getValue('awesomeURL') . '">';
        }
    }
    public function beforeAdminLoad()
    {
        if (!$this->getValue('enableFaviconAdmin')) {
            return;
        }
        ob_start();
    }
    public function afterAdminLoad()
    {
        if (!$this->getValue('enableFaviconAdmin')) {
            return;
        }
        $html = ob_get_clean();
        $replaceMessage = '<!-- Favicon replaced by SettingsPlus -->';

        $faviconURL = PATH_UPLOADS . $this->pluginName . DS . basename($this->getValue('faviconAdmin'));
        if (file_exists($faviconURL))
        {
            $faviconWidth = $faviconURL ? getimagesize($faviconURL)[0] : 0;
            $faviconHeight = $faviconURL ? getimagesize($faviconURL)[1] : 0;
            $newFavicon = '<link rel="icon" type="image/png"' . ' sizes="' . $faviconWidth . 'x' . $faviconHeight . '" href="' . DOMAIN_BASE . ltrim($this->getValue('faviconAdmin'), '/') . '?' . time() . '">';
            $pattern = '/<link[^>]*rel="[^"]*"[^>]*href="[^"]*favicon[^"]*"[^>]*>/i';
            if (preg_match($pattern, $html)) {
                // マッチした（元々あった）タグを、新しいタグに完全に置換する
                $html = preg_replace($pattern, $replaceMessage . $newFavicon, $html);
            } else {
                // 万が一マッチしなかった場合の保険として、</head> の直前に強制挿入
                $html = str_replace('</head>', $replaceMessage . $newFavicon . "\n</head>", $html);
            }
        }

        echo $html;
    }
    public function adminHead()
    {
        // Font Awesome の読み込み
        if ($this->getValue('useFontAwesome')) {
            echo '<link rel="stylesheet" href="' . $this->getValue('awesomeURL') . '">';
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
                            $("<span>").addClass("nav-link alert alert-secondary")
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
