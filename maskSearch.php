<?php
require_once('vendor/autoload.php');
/**
 *  健保特約機構口罩剩餘數量明細清單 
 *  data from https://data.nhi.gov.tw/Datasets/DatasetDetail.aspx?id=656
 */

if (!empty($argv[1])) {
    $climate = new League\CLImate\CLImate;
    $file = fopen("maskdata.csv", "r");

    $tableData = [];
    $tableData[] = ['醫事機構名稱','醫事機構地址','成人口罩剩餘數'];
    
    // 輸入的關鍵字
    $keyword = $argv[1];

    while ($csvData = fgetcsv($file)) {
        // 將搜尋到的地址加入tableData
        $pos = strpos($csvData[2], $keyword);
        if ($pos !== false) {
            $arr = [];
            $arr[] = $csvData[1];  // 醫事機構名稱
            $arr[] = $csvData[2];  // 醫事機構地址
            $arr[] = $csvData[4];  // 成人口罩剩餘數
            $tableData[] = $arr;
        } 
    }

    // 依成人口罩剩餘數排序，大到小
    usort($tableData, function($a, $b) {
        return $b[2] <=> $a[2];
    });

    $climate->table($tableData);
    if (count($tableData) <= 1) {
        echo '找不到此搜尋的結果';
    }
    fclose($file);
}else {
    echo '請輸入搜尋地址';
}