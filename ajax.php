<?php
include('config.php');
// https://docs.google.com/spreadsheets/d/1R8yd6608Mux8tYlX2tNqNmOiKtrsxJDKJaxlHWRYBHU/edit#gid=0
$spreadsheetId='1R8yd6608Mux8tYlX2tNqNmOiKtrsxJDKJaxlHWRYBHU';
$range='工作表1';
$todo=$_REQUEST["todo"];
$uid=$_REQUEST["uid"];
$name=$_REQUEST["name"];
$money=$_REQUEST["money"];

$response=$service->spreadsheets_values->get($spreadsheetId,$range);
$values=$response->getValues();

//簡單的過濾一下
if($uid == "員工編號" or empty($uid)){
    echo $uid." 資料怪怪的，請檢查...";
    exit;
}

switch($todo){
    
    //查詢資料
	case 'select':
        foreach($values as $row){
		    if($uid === $row[0]){
		        $msg = "員工:".$uid."是[".$row[1]."]，已有[".$row[2]."]年資歷。";
		    }
		}
		if(strlen($msg)>=1){
		    echo $msg;
		}else{
		    echo "查不到".$uid."資料";
		}
	break;
	
	//新增資料
	case 'insert':
        foreach($values as $row){
		    if($uid === $row[0]){
		        echo $msg = "編號重複了！";
		        exit;
		    }
		}
	    try {
	        if((strlen($uid)>=1) && (strlen($name)>=1)){
                $values = [
                    [$uid, "$name", "0", "18", "0", $uid."@mail.com"]
                ];
                $appendBody = new Google_Service_Sheets_ValueRange([
                    'values' => $values
                ]);
                $params = [
                    'valueInputOption' => 'USER_ENTERED'
                ];
                $result = $service->spreadsheets_values->append($spreadsheetId, $range, $appendBody, $params);
                echo '已新增： '.$result->getUpdates()->getUpdatedCells()." 筆欄位資料。";
	        }else{
	            echo "新增資料失敗。";
	        }
        } catch (Google_Exception $e) {
            $errors = json_decode($e->getMessage(),true);
            $err = "code : ".$errors["error"]["code"]."\r\n";
            $err = "message : ".$errors["error"]["message"];
            echo "Google_Exception".$err;
        }
	    
	break;
	
	//更新資料
	case 'update':
	    if(!preg_match("/^\d{1,6}$/",$money)){
            echo "只能輸入小於一百萬的獎金。";
            exit;
        }
	    $i = 0;
        if (empty($values)) {
            echo "No data found.\n";
            exit;
        } else {
            foreach ($values as $row) {
                $i++; //放在這跳過標題列
                if ($uid === $row[0]) {
                    $update_range = $range.'!E'.$i; //要更新的指定欄位
                    try {
                        $values = [
                            [$money] //要送出更新的資料
                        ];
                        $appendBody = new Google_Service_Sheets_ValueRange([
                            'values' => $values
                        ]);
                        $params = [
                            'valueInputOption' => 'USER_ENTERED'
                        ];
                        $result = $service->spreadsheets_values->update($spreadsheetId, $update_range, $appendBody, $params);
                        $msg = "已給員工：".$uid." 發大財獎金：".$money;
                    } catch (Google_Exception $e) {
                        $errors = json_decode($e->getMessage(),true);
                        $err = "code : ".$errors["error"]["code"]."\r\n";
                        $err = "message : ".$errors["error"]["message"];
                        echo "Google_Exception".$err;
                    }
                }
            }
        }
		if(strlen($msg)>=1){
		    echo $msg;
		}else{
		    echo "查不到".$uid."資料";
		}
	break;
	
	//刪除資料
	case 'delete':
	    $i = 0;
	    if (empty($values)) {
            echo "No data found.\n";
            exit;
        } else {
            foreach ($values as $row) {
                $i++; //放在這邊計算跳過標題列
                if ($uid === $row[0]) {
                    try {
                        /* // 這裡用來做清空整列
                        $requestBody = new Google_Service_Sheets_BatchClearValuesRequest([
                            'ranges'=>'工作表1!'.$i.':'.$i //要清空的範圍
                        ]);
                        $response = $service->spreadsheets_values->batchClear($spreadsheetId, $requestBody);
                        */
        
                        //這裡用來刪除整列
                        $requests = new Google_Service_Sheets_Request(array(
                            'deleteDimension' => array(
                                'range' => array(
                                    'sheetId' => 1118095092,
                                    'dimension' => "ROWS",
                                    'endIndex' => $i,
                                    'startIndex' => --$i //用來計算刪除列的範圍，這邊-1計算後，下面+1回來
                                )
                            )
                        ));
                        $i++; //如上註解，這裡+1回來
                        $requestBody = new Google_Service_Sheets_BatchUpdateSpreadsheetRequest([
                            'requests' => $requests
                        ]);
                        $response = $service->spreadsheets->batchUpdate($spreadsheetId, $requestBody);
                        $msg = "倒楣的".$row[1]."已被開除！編號：".$uid;
                    } catch (Google_Exception $e) {
                        $errors = json_decode($e->getMessage(),true);
                        $err = "code : ".$errors["error"]["code"]."\r\n";
                        $err = "message : ".$errors["error"]["message"];
                        echo "Google_Exception".$err;
                    }
                }
            }
        }
		if(strlen($msg)>=1){
		    echo $msg;
		}else{
		    echo "查不到".$uid."資料";
		}
	break;
	default:
		echo "抱歉，資料有誤，什麼都沒發生...";
	break;
}