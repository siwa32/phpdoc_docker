# PHP日本語マニュアルビルドコンテナー

![License](https://img.shields.io/badge/license-MIT-blue.svg)

## 概要

jdkfxさんが作成した [PHP 日本語マニュアル　翻訳用リポジトリ](https://github.com/jdkfx/phpdoc) のビルド環境をコンテナーで実行するためdocker環境です。

※ビルド環境を用意してくれたjdkfxさんありがとうございます！この場を借りてお礼申し上げます。

## 準備

以下のようなディレクトリ構成を作成する

```txt
/path/to/phpdoc_translation
├── phpdoc              # PHP 日本語マニュアル ビルド環境をクローン
├── doc-ja              # 日本語版のドキュメントをフォークしてクローン
├── doc-en              # 英語版のドキュメントをクローン
└── phpdoc_docker       # このリポジトリをクローン
```

- 日本語版のドキュメント：https://github.com/php/doc-ja
- 英語版のドキュメント：https://github.com/php/doc-en

## マウント位置

以下の位置にマウントされます

```txt
/path/to/phpdoc_translation
├── phpdoc              # /workspaces 
├── doc-ja              # /workspaces/ja 
├── doc-en              # /workspaces/en 
└── phpdoc_docker       
    └── search          # /workspaces/output/php-chunked-xhtml/search
```

## コンテナー起動

```shell
make up
```

## コンテナーに入る

```shell
make php
```

## phpdocのセットアップ

コンテナーで実行

```shell
make setup
```

phpdocの説明にあるディレクトリ構成がコンテナー内に作成されます。


## ビルド

コンテナーで実行

```shell
make build
make xhtml
```

## 表示

php組み込みサーバが起動しているのでホストのブラウザから以下URLにアクセスするとトップページにアクセスします。

http://127.0.0.1:8887/

以下URLではビルドされたhtmlファイルをアンド検索可能なページにアクセスできます。

http://127.0.0.1:8887/search/

※翻訳ファイルを追加したり削除してビルド↓場合は `./search/files.json` を削除してください。
