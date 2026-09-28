# SettingsPlus

[Bludit](https://www.bludit.com/) の管理画面（admin）に、標準では用意されていない便利な設定項目を追加するプラグインです。

## 主な機能

- **サイトタイトルの表示**
  管理画面のサイドバーにあるロゴ下に、サイトタイトルを表示するかどうかを切り替えられます。
- **コンテンツのカテゴリ名表示**
  管理画面のコンテンツ一覧で、各ページのURL欄にカテゴリ名を表示するかどうかを切り替えられます。
- **管理画面用ファビコンの変更**
  管理画面のファビコンを、任意の PNG 画像にアップロードして差し替えられます。
- **Font Awesome の読み込み**
  管理画面に Font Awesome を読み込むかどうかを切り替え、任意の CDN / URL を指定できます。

## インストール方法

1. このリポジトリを `bl-plugins/SettingsPlus` としてBluditの `bl-plugins` ディレクトリに配置します。
2. Bludit 管理画面の「プラグイン」ページから **Settings Plus** を有効化します。
3. サイドバーに追加される「Settings Plus」から設定画面を開きます。

## 動作要件

| 項目 | バージョン |
| --- | --- |
| Bludit | 3.22 以降 |

`metadata.json` の `compatible` に記載のバージョンを参照してください。

## 設定項目

| 項目 | 説明 |
| --- | --- |
| Display site title in the admin sidebar | 管理画面サイドバーにサイトタイトルを表示するかどうか |
| Display content category name | コンテンツ一覧の各ページにカテゴリ名を表示するかどうか |
| Favicon in the admin | 管理画面のファビコンを差し替えるかどうか、および差し替え用の PNG 画像 |
| Use Font Awesome | Font Awesome を読み込むかどうか、および読み込み先 URL |

> ファビコンをアップロードした後は、ブラウザキャッシュのクリアが必要な場合があります。

## ライセンス

[MIT License](LICENSE)

