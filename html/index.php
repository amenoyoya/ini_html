<?php
/**
 * file_get_contents:
 *   ファイルがない場合 E_WARNING 出力するため、@演算子でエラー無視させる
 *   $http_response_header でエラーハンドリングしておくとより良い
 */
if (null === ($content = @file_get_contents('./test.html'))) {
    if (is_array($http_response_header) && count($http_response_header) > 0) {
        $status_code = explode(' ', $http_response_header[0]);
        switch ($status_code[1]) {
            case 404:
                throw new Exception('指定したページが見つかりませんでした');
                break;
            case 500:
                throw new Exception('指定したページがあるサーバーにエラーがあります');
                break;
            default:
                throw new Exception('指定したページのデータを取得できませんでした');
        }
    } else {
        //タイムアウトの場合 or 存在しないドメインだった場合
        throw new Exception("URLが間違っています");
    }
}

/**
 * ::meta:: ～ ::end:: の文字列を取得する
 * preg_match:
 *   / pattern /s: 改行を無視して一行をみなす（複数行マッチ）
 *   / ?! (pattern) /: 否定先読み（pattern に合致しない文字列にマッチ）
 *   / .+? /: 任意の1文字以上にマッチ（最短マッチ）
 *   PREG_OFFSET_CAPTURE: マッチ位置も取得
 *   &$result:
 *     [0] => [[0] => マッチ文字列全文, [1] => マッチ位置]
 *     [1...] => [[0] => キャプチャ文字列], [1] => マッチ位置]
 */
$match = preg_match('/::meta::((?! ::end::).+?)::end::/s', $content, $result, PREG_OFFSET_CAPTURE);

// マッチした部分は削除
$content = substr($content, 0, $result[0][1]) . substr($content, $result[0][1] + strlen($result[0][0]));

// メタ情報
$title = '';
$description = '';
$keywords = [];

// キャプチャした部分を ini として処理
if ($ini = @parse_ini_string($result[1][0])) {
    $title = isset($ini['title'])? $ini['title']: '';
    $description = isset($ini['description'])? $ini['description']: '';
    $keywords = is_array($ini['keywords'])? $ini['keywords']: [];
    $title = isset($ini['title'])? $ini['title']: '';
}

// テンプレートファイル展開
include('./template.php');
