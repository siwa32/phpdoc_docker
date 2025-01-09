<?php
/**
 * `ouput/php-chunked-xhtml/` ディレクトリに存在するファイルを検索する
 * 空白区切りでand検索が可能
 *
 * `ouput/php-chunked-xhtml/search` ディレクトリに以下のツリーとなるように配置する
 * + ouput/php-chunked-xhtml/
 *   + search/
 *     + index.php (このファイル)
 *     + linkicon.php アイコンsvgファイル
 *     + files.json (自動生成キャッシュファイル)
 * ```
 *
 * htmlファイルを追加・削除した場合はfiles.jsonを削除する
 * 次回ページ表示時に再生成される
 *
 * 以下ほぼすべてchat-gptにより生成されたコード
 */

// 定数で output/php-chunked-xhtml のディレクトリを設定
const TARGET_DIRECTORY = "/workspaces/output/php-chunked-xhtml";
// ファイル一覧をキャッシュするファイル名
const FILES_JSON = "files.json";

// 指定されたディレクトリ内のファイル一覧を取得してJSON配列として出力する関数
function listFiles(string $directory): array {
    // ディレクトリが存在するか確認
    if (!is_dir($directory)) {
        return [];
    }

    // ディレクトリ内のファイルをスキャン
    $files = scandir($directory);

    // . と .. を除外
    $filteredFiles = array_values(array_filter($files, function ($file) use ($directory) {
        return !in_array($file, ['.', '..']) && is_file($directory . DIRECTORY_SEPARATOR . $file);
    }));

    // .htmlを削除（拡張子は決まっているので決め打ち）
    $filteredFiles = array_map(fn ($file) => basename($file, ".html"), $filteredFiles);

    return $filteredFiles;
}

// 実行
if (file_exists(FILES_JSON)) {
    $json = file_get_contents(FILES_JSON);
    $files = json_decode($json, true);
} else {
    $files = listFiles(TARGET_DIRECTORY);
    $json = json_encode($files, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT);
    file_put_contents(FILES_JSON, $json);
}
$files_array = $json;

?>
<!DOCTYPE html>
<html lang="ja">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>ファイル検索</title>
  <style>
    body {
      font-family: Arial, sans-serif;
      margin: 20px 4rem;
    }
    ul {
      list-style-type: none;
      padding: 0;
    }
    li {
      margin: 5px 0;
    }
    a:after {
      content: "";
      background-image: url("./linkicon.svg");
      display: inline-block;
      height: 1rem;
      width: 1rem;
      vertical-align: middle;
    }
    h1 {
      font-size: 1.75rem;
      font-weight: 500;
      color: #793862;
    }
    h1:after {
      display: table;
      width: 100%;
      content: " ";
      border-bottom: 1px dotted;
    }
    #filterInput {
      width: 80%;
    }
  </style>
</head>
<body>
  <h1>ファイル検索</h1>
  <input type="text" id="filterInput" placeholder="Type something...">
  <ul id="resultList"></ul>

  <script>
    // Sample array of strings
    const dataArray = <?= $files_array ?>;
    if (dataArray.length === 0) {
      console.error('file list is empty.');
    }

    // Debounce delay in milliseconds
    const DEBOUNCE_DELAY = 300;
    const ENABLE_KEYWORDS_LENGTH = 2;// 検索語として有効な最小文字数

    // Function to filter and display the results
    const filterAndDisplay = () => {
      const input = document.getElementById('filterInput').value.toLowerCase().trim();
      const resultList = document.getElementById('resultList');
      resultList.innerHTML = '';

      if (input.length < ENABLE_KEYWORDS_LENGTH) {
        return;
      }
      const keywords = input.split(/\s+/).filter(item => item.length >= ENABLE_KEYWORDS_LENGTH); // Split by spaces and remove short strings
      if (keywords.length === 0) {
        return;
      }

      const filteredData = dataArray.filter(item =>
        keywords.every(keyword => item.toLowerCase().includes(keyword)) // Match all keywords
      ).sort(); // Sort the results alphabetically

      filteredData.forEach(item => {
        const li = document.createElement('li');
        const link = document.createElement('a');
        const re = new RegExp(keywords.join('|'), 'g');
        link.innerHTML = item.replace(re, '<span style="background-color:yellow">$&</span>');
        link.href = `../${item}.html`;
        link.target = "phpdoc_blank"; // Open link in a new tab
        li.appendChild(link);

        resultList.appendChild(li);
      });
    };

    // Debounce function to limit the frequency of calls
    const debounce = (func, delay) => {
      let timeout;
      return (...args) => {
        clearTimeout(timeout);
        timeout = setTimeout(() => func(...args), delay);
      };
    };

    // Attach the debounced filter function to the input event
    const debouncedFilterAndDisplay = debounce(filterAndDisplay, DEBOUNCE_DELAY);
    document.getElementById('filterInput').addEventListener('input', debouncedFilterAndDisplay);
  </script>
</body>
</html>
