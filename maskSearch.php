<?php
/** 引用autoload */
require_once('vendor/autoload.php');

/**
 *  健保特約機構口罩剩餘數量明細清單 
 *  data from https://data.nhi.gov.tw/Datasets/DatasetDetail.aspx?id=656
 */

/** 判斷是否有輸入關鍵字 */
if (!empty($argv[1])) {
    /** 使用CLImate */
    $climate = new League\CLImate\CLImate;
    /** @param stream 匯入maskdata.csv的資料 */
    $maskDataCSV = fopen("maskdata.csv", "r");

    /** @param array 要匯出的資料陣列 */
    $tableData = [];
    $tableData[] = ['醫事機構名稱','醫事機構地址','成人口罩剩餘數'];
    
    /** 輸入的關鍵字 */ 
    $keyword = $argv[1];

    /** 讀取一行csv的資料 */
    while ($csvData = fgetcsv($maskDataCSV)) {
        /** 返回字符串在另一個字符串中第一次出現的位置。 */
        $pos = strpos($csvData[2], $keyword);
        /** 將搜尋到的地址加入tableData */ 
        if ($pos !== false) {
            $rowArray = [];
            $array[] = $csvData[1];  /** 醫事機構名稱 */
            $array[] = $csvData[2];  /** 醫事機構地址 */ 
            $array[] = $csvData[4];  /** 成人口罩剩餘數 */ 
            $tableData[] = $rowArray;
        } 
    }

    /** 依成人口罩剩餘數排序，大到小 */
    usort($tableData, function($a, $b) {
        return $b[2] <=> $a[2];
    });

    /** 將結果輸出 */
    $climate->table($tableData);
    if (count($tableData) <= 1) {
        echo '找不到此搜尋的結果';
    }
    fclose($maskDataCSV);
} else {
    echo '請輸入搜尋地址';
}