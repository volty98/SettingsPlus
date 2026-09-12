<?php

class pluginSettingsPlus extends Plugin
{
    private $cssFile;

    public function init()
    {
        // プラグインフォルダ
        $this->cssFile = $this->domainPath() . 'css/SettingsPlus.css';
    }

    /* ---------------------------------------------------------
     * 管理画面フォーム
     * --------------------------------------------------------- */
    public function form()
    {
        $html  = '<div class="card">';
        $html .= '<div class="card-body">';

        $html .= '<h4 class="card-title">Settings Plus</h4>';
        $html .= '<p class="card-text">Extension to Settings</p>';

        $html .= '</div>'; // card-body
        $html .= '</div>'; // card

        return $html;
    }

    /* ---------------------------------------------------------
     * 管理画面 POST
     * --------------------------------------------------------- */
    public function post()
    {

    }

    /* ---------------------------------------------------------
     * Admin ページの HTML を拡張
     * --------------------------------------------------------- */
    public function adminHead()
    {
        global $page;

        // CSS ファイルを読み込む
        echo '<link rel="stylesheet" href="' . $this->cssFile . '">';
    }
    public function adminBodyEnd()
    {
        global $page;

        $html = '<script>
            $(document).ready(function() {
                // /admin/settings の次にliを追加してメニューを拡張する
                $("a[href=\"/admin/settings\"]").parent().after("<li class=\"nav-item\"><a class=\"nav-link\" href=\"/admin/configure-plugin/pluginSettingsPlus\"><span class=\"fa fa-gears\"></span>Settings Plus</a></li>");
            });
        </script>';
        
        return $html;
        // return $page; // この行は不要になったのでコメントアウト
    }

    public function adminSidebar()
    {
        global $page;

        // サイドバーに「Settings Plus」リンクを追加
        // echo '<li class="nav-item">
        //     <a class="nav-link" href="/admin/settings-plus">Settings Plus</a>
        // </li>';

        return $page;

    }



    /* ---------------------------------------------------------
     * Utility
     * --------------------------------------------------------- */
    private function uuid()
    {
        return bin2hex(random_bytes(16));
    }

}
