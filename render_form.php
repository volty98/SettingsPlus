<?php
    // $this を使ってメソッドを呼べる
    // form()でインクルードされるので完全に埋め込まれる

    // 管理画面フォームの HTML を生成
    $html_render = '<div class="alert alert-primary" role="alert">';
    $html_render .= $this->description();
    $html_render .= '</div>';

    $html_render .= '<h6 class="mt-4 mb-2 pb-2 border-bottom text-uppercase">' . 'Admin settings' . '</h6>';

    // サイトタイトル表示設定
    $html_render .= '<div class="form-group row">';
    $html_render .= '<label class="col-sm-4 col-form-label" for="displaySiteTitle">' . 'Display site title' . '</label>';
    $html_render .= '<div class="col-sm-8">';
    $html_render .= '<select class="custom-select" id="displaySiteTitle" name="displaySiteTitle">';
    $html_render .= '<option value="true" ' . ($this->getValue('displaySiteTitle') === true ? 'selected' : '') . '>Enabled</option>';
    $html_render .= '<option value="false" ' . ($this->getValue('displaySiteTitle') === false ? 'selected' : '') . '>Disabled</option>';
    $html_render .= '</select>';
    $html_render .= '<small class="form-text text-muted">' . 'Display the site title in the admin sidebar.' . '</small>';
    $html_render .= '</div>';
    $html_render .= '</div>';

    // コンテンツページ、各ページのカテゴリバッジ表示設定
    $html_render .= '<div class="form-group row">';
    $html_render .= '<label class="col-sm-4 col-form-label" for="displayContentCategoryName">' . 'Display content category name' . '</label>';
    $html_render .= '<div class="col-sm-8">';
    $html_render .= '<select class="custom-select" id="displayContentCategoryName" name="displayContentCategoryName">';
    $html_render .= '<option value="true" ' . ($this->getValue('displayContentCategoryName') === true ? 'selected' : '') . '>Enabled</option>';
    $html_render .= '<option value="false" ' . ($this->getValue('displayContentCategoryName') === false ? 'selected' : '') . '>Disabled</option>';
    $html_render .= '</select>';
    $html_render .= '<small class="form-text text-muted">' . 'Display the content category name in the content.' . '</small>';
    $html_render .= '</div>';
    $html_render .= '</div>';

    // adminのファビコンにサイトのファビコンを適用させる
    $html_render .= '<div class="form-group row">';
    $html_render .= '<label class="col-sm-4 col-form-label" for="enableFaviconAdmin">' . 'Favicon in the admin' . '</label>';
    $html_render .= '<div class="col-sm-8">';
    $html_render .= '<select class="custom-select" id="enableFaviconAdmin" name="enableFaviconAdmin">';
    $html_render .= '<option value="true" ' . ($this->getValue('enableFaviconAdmin') === true ? 'selected' : '') . '>Enabled</option>';
    $html_render .= '<option value="false" ' . ($this->getValue('enableFaviconAdmin') === false ? 'selected' : '') . '>Disabled</option>';
    $html_render .= '</select>';
    $html_render .= '<div class="container shadow-sm p-3 my-2 bg-white rounded item-align-center">';
    $html_render .= '<input id="faviconAdminFile" name="faviconAdminFile" type="file" accept="image/png" style="display:none;">';
    $html_render .= '<div>';
    $html_render .= '<img class="m-1 p-0" id="faviconAdminFilename" src="' . $this->getValue('faviconAdmin') . '" alt="Favicon" style="width:64px; height:64px;">';
    $html_render .= '<label for="faviconAdminFile" class="btn btn-sm btn-outline-secondary ml-3 align-baseline">Upload favicon</label>';
    $html_render .= '<small class="form-text text-muted">Need to clear cache after uploading a new favicon.</small>';
    $html_render .= '</div>';
    $html_render .= '</div>';
    $html_render .= '</div>';
    $html_render .= '</div>';

    // $html_render .= '<h6 class="mt-4 mb-2 pb-2 border-bottom text-uppercase">' . 'Admin & website settings' . '</h6>';

    // Font Awesome 使用設定
    $html_render .= '<div class="form-group row">';
    $html_render .= '<label class="col-sm-4 col-form-label">' . 'Use Font Awesome' . '</label>';
    $html_render .= '<div class="col-sm-8">';
    $html_render .= '<select class="custom-select mb-3" id="useFontAwesome" name="useFontAwesome">';
    $html_render .= '<option value="true" ' . ($this->getValue('useFontAwesome') === true ? 'selected' : '') . '>Enabled</option>';
    $html_render .= '<option value="false" ' . ($this->getValue('useFontAwesome') === false ? 'selected' : '') . '>Disabled</option>';
    $html_render .= '</select>';
    $html_render .= '<div class="input-group">';
    $html_render .= '<input type="text" class="form-control" name="awesomeURL" value="' . $this->getValue('awesomeURL') . '" placeholder="Font Awesome URL">';
    $html_render .= '</div>';
    $html_render .= '<small class="form-text text-muted">Specify the URL for the Font Awesome CSS file.</small>';
    $html_render .= '<hr>';
    $html_render .= '<div class="d-flex align-items-center">';
    $html_render .= '<span class="border p-1 mr-2 bg-light rounded"><i class="fa-regular fa-house fa-2x"></i></span>';
    $html_render .= '<p class="align-baseline m-0">' . 'When display house icon, it is successfully loaded.' . '</p>';
    $html_render .= '</div>';
    $html_render .= '</div>';
    $html_render .= '</div>';

    // メンテナンスモード設定
    $html_render .= '<div class="form-group row">';
    $html_render .= '<label class="col-sm-4 col-form-label" for="maintenanceMode">' . 'Maintenance mode' . '</label>';
    $html_render .= '<div class="col-sm-8">';
    $html_render .= '<select class="custom-select" id="maintenanceMode" name="maintenanceMode">';
    $html_render .= '<option value="true" ' . ($this->getValue('maintenanceMode') === true ? 'selected' : '') . '>On</option>';
    $html_render .= '<option value="false" ' . ($this->getValue('maintenanceMode') === false ? 'selected' : '') . '>Off</option>';
    $html_render .= '</select>';
    $html_render .= '<small class="form-text text-muted">' . 'Enable maintenance mode to temporarily take the site offline.' . '</small>';
    $html_render .= '<div class="input-group">';
    $html_render .= '<input type="text" class="form-control" name="maintenanceMessage" value="' . $this->getValue('maintenanceMessage') . '" placeholder="Maintenance message">';
    $html_render .= '</div>';
    $html_render .= '<small class="form-text text-muted">' . 'This message will be displayed when the site is in maintenance mode.' . '</small>';
    $html_render .= '</div>';
    $html_render .= '</div>';

    // ファイル選択後に自動送信
    $html_render .= '<script>';
    $html_render .= 'document.getElementById("faviconAdminFile").addEventListener("change", function() {';
    $html_render .= '  var form = document.querySelector("form");';
    $html_render .= '  if (form) form.submit();';
    $html_render .= '});';
    $html_render .= '</script>';

    // フォーム全体に enctype を設定
    $html_render .= '<script>';
    $html_render .= 'document.addEventListener("DOMContentLoaded", function() {';
    $html_render .= '  var form = document.querySelector("form");';
    $html_render .= '  if (form) form.setAttribute("enctype", "multipart/form-data");';
    $html_render .= '});';
    $html_render .= '</script>';
    
    // クラス名 .plugin-form を削除
    // 強制的に適用される plugin-form クラスを削除してレイアウトを調整する
    // 代償として、プラグインフォームに適用されるスタイルが失われる可能性があるが、本家Generalの管理画面ににたレイアウトに近づけることができる。
    // したがってdiv class デザインはBLUDIT Adminで使えるものに絞って設計する
    $html_render .= '<script>';
    $html_render .= 'document.addEventListener("DOMContentLoaded", function() {';
    $html_render .= '  var form = document.querySelector("form.plugin-form");';
    $html_render .= '  if (form) {';
    $html_render .= '    form.classList.remove("plugin-form");';
    $html_render .= '    console.log("Plugin form class removed.");';
    $html_render .= '  }';
    $html_render .= '});';
    $html_render .= '</script>';

    // デバッグ要素として実際に保存された値を表示
    $html_render .= '<div id="accordion">
        <hr>
        <label><a data-toggle="collapse" href="#dbg">Actual saved value (debug)<i class="fa fa-chevron-down"></i></a></label>
        <div class="card-body card metric-card" style="display:block;">
        <div id="dbg" class="collapse">';
    $html_render .= 
        '<h4>DB</h4><pre>' . var_export($this->db, true) . "(" . gettype($this->db) . ")" . "</pre>" .
        '<h4>layout</h4><pre>' . var_export($layout, true) . "(" . gettype($layout) . ")" . "</pre>";
    $html_render .='</div>
        </div>
        </div>';

?>

