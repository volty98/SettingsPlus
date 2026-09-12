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
        $html .= '<p class="card-text">Extension to &lt;Settings - General&gt;</p>';

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
    public function adminPageBegin()
    {
        global $page;

        if ($page->slug() !== 'admin') return;

        echo '<link rel="stylesheet" href="' . $this->cssFile . '">';
    }

    public function adminPageEnd()
    {
        global $page;

        if ($page->slug() !== 'admin') return;

        // '/admin/settings'のliの表示に「PLUS」を追加
        echo '<script>
        document.addEventListener("DOMContentLoaded", function() {
            var settingsLi = document.querySelector("li a[href=\'/admin/settings\']");
            if (settingsLi) {
                settingsLi.innerHTML += " <span class=\"badge badge-primary\">PLUS</span>";
            }
        });
        </script>';

    }



    /* ---------------------------------------------------------
     * Utility
     * --------------------------------------------------------- */
    private function uuid()
    {
        return bin2hex(random_bytes(16));
    }

}
