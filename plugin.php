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
            'displayContentCategoryName' => false,
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

        // ~ ~ ~ ~ ~ ~ ~ ~ ~ ~
        // HTML生成は render_form.php に委譲
        // includeなので$pageや$layout変数をそのまま使用できる
        $html_render = ''; // use in render_form.php
        include($this->phpPath() . 'render_form.php');
        // ~ ~ ~ ~ ~ ~ ~ ~ ~ ~

        return $html_render;
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
     * Web site のHTMLを拡張
     * --------------------------------------------------------- */
    public function siteHead()
    {
        // use Font Awesome
        if ($this->getValue('useFontAwesome')) {
            echo '<link rel="stylesheet" href="' . $this->getValue('awesomeURL') . '">';
        }
    }
    /* ---------------------------------------------------------
     * Admin ページのHTMLを拡張
     * --------------------------------------------------------- */
    public function beforeAdminLoad()
    {
        // Favicon in the admin
        if (!$this->getValue('enableFaviconAdmin')) {
            return;
        }
        ob_start();
    }
    public function afterAdminLoad()
    {
        // Favicon in the admin
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
        global $site, $layout, $categories, $pages;

        $html = '';

        if($layout['view'] == 'content' && $this->getValue('displayContentCategoryName')) {
            // ページとカテゴリのマッピングを作成
            $items = $pages->getList(1, -1, true);
            $pageCategoryMap = [];

            foreach ($items as $key) {
                $p = buildPage($key);
                $k = $p->key();
                $c = $p->category();
                if(!empty($c)) {
                    $pageCategoryMap[$k] = $c;
                }
                else {
                    // カテゴリが空の場合はカテゴリマップに追加しない
                    // $pageCategoryMap[$k] = '';
                }
                // echo $p->category() . ': ' . $p->key() . '<br>';
            }
            // JSONとしてJS変数を定義
            $jsCategories = json_encode($pageCategoryMap);
            $html .= '<!-- Display content category name -->';
            $html .= '<script> var pageCategoryMap = ' . $jsCategories . ';</script>';
            $html .= '<script>
                $(document).ready(function() {
                    $("tbody td.contentURL a").each(function() {
                        var title = $(this).attr("title");
                        
                        if (title) {
                            // スラッシュ以降の文字列をキーとして抽出
                            var parts = title.split("/");
                            var pageKey = parts.pop(); 
                            
                            // マップに存在すればカテゴリを表示
                            if (pageCategoryMap.hasOwnProperty(pageKey)) {
                                var categoryName = pageCategoryMap[pageKey];
                                $(this).closest("td.contentURL").prepend(
                                    \'<span class="align-middle mr-1">\' + "(" + categoryName + ")" + \'</span>\'
                                );
                            }
                        }
                    });
                });
                </script>';
        }

        if ($this->getValue('displaySiteTitle')) {
            // Site title in the admin sidebar
            $title = $site->title();

            $html .= '<script>
                $(document).ready(function() {
                    // BLUDIT テキストを含む li を探す
                    var bluditLi = $(".nav-item").filter(function() {
                        return $(this).text().includes("BLUDIT");
                    });
                    
                    if (bluditLi.length > 0) {
                        // 新しい li 要素を作成
                        var newLi = $("<li>").addClass("nav-item")
                            .append(
                                $("<span>").addClass("nav-link alert alert-secondary text-center rounded-pill py-0")
                                .text("' . htmlspecialchars($title) . '")
                            );
                        
                        // BLUDIT の次に挿入
                        bluditLi.after(newLi);
                    }
                });
            </script>';
        }        
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

    public function adminView()
    {
    }


    /* ---------------------------------------------------------
     * Utility
     * --------------------------------------------------------- */
    private function uuid()
    {
        return bin2hex(random_bytes(16));
    }

}
