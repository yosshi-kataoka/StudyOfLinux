##
WikipediaLogAnalysis

##
概要

このプロジェクトでは、データベースに保存したWikiPediaLogを下記の通り表示することができます。

①総閲覧数の多い順にドメイン名、総閲覧数を表示

②指定したドメイン名よりドメイン名、総閲覧数を多い順に表示

データベースに保存したWikiPediaLogのURLは以下の通りです。

https://dumps.wikimedia.org/other/pageviews/2021/2021-12/

// データベースに含まれる情報

1.ドメイン名

2.ページタイトル

3.総閲覧数

##
目次

1.[インストール]

2.[使用方法]

##
インストール

下記コマンドにてプロジェクトをpull

git clone https://github.com/yosshi-kataoka/logAnalysisProgram.git

##
使用方法

logAnalyses/src/src/lib/Main.php

// 上記コマンドにて以下がコマンドラインに表示されます。

wikipediaのログを解析します。

1または2を入力してください。

------------------------------

1:閲覧数の多い記事をランキング順に表示

2:ドメインごとの最も多い閲覧数を表示

// 実行したい処理を1もしくは２を標準入力より入力

//　1を入力した場合、下記が表示されるので、1以上の整数を入力する

表示させたい記事数の値(整数)を入力してください。

------------------------------

//　表示例) 下記は2を入力した場合

// ドメイン名

// ページタイトル

// 総閲覧数

en.m

Main_Page

122058

------------------------------
en

Main_Page

69181

------------------------------

// 2を入力した場合、下記が表示されるので、ドメイン名を半角スペースで区切って入力する

表示させたいドメイン名を入力してください。(ドメイン名を半角スペースで区切ることで複数のドメイン名を入力可能です。)

入力例） en de ja

------------------------------

//　表示例) 下記はen de jaを入力した場合

// ドメイン名

// 総閲覧数

en de ja

en

69181

------------------------------
de

20739

------------------------------
ja

18475

------------------------------
