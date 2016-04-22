<?php
	//一些接口辅助方法
	
	    /**
     * 将日期格式根据以下规律修改为不同显示样式
     * 小于1分钟 则显示多少秒前
     * 小于1小时，显示多少分钟前
     * 一天内，显示多少小时前
     * 3天内，显示前天22:23或昨天:12:23。
     * 超过3天，则显示完整日期。
     * @static
     * @param  $sorce_date 数据源日期 unix时间戳
     * @return void
     */
     function getDateStyle($sorce_date){

        $nowTime = time();  //获取今天时间戳

        //echo '数据源时间戳：'.$sorce_date . ' = '. date('Y-m-d H:i:s',$sorce_date);
        //echo "<br/> 当前时间戳:". date('Y-m-d H:i:s', $nowTime)."\n";

        $timeHtml = 0; //返回文字格式
        $temp_time = 0;
        switch($sorce_date){

            //一分钟
            case ($sorce_date+60)>= $nowTime:
                $temp_time =  $nowTime-$sorce_date;
				if($temp_time<0){
					$temp_time = 1;	
				}
                $timeHtml = $temp_time ."秒前";
                break;

            //小时
            case ($sorce_date+3600)>= $nowTime:
                $temp_time = floor(($nowTime-$sorce_date)/60);
				if($temp_time<0){
					$temp_time = 1;	
				}
                $timeHtml = $temp_time."分钟前";
                break;
            //天
            case ($sorce_date+3600*24)>= $nowTime:
                $temp_time = floor(($nowTime-$sorce_date)/3600);
				if($temp_time<0){
					$temp_time = 1;	
				}
                $timeHtml = $temp_time.'小时前';
                break;

            //昨天
            case ($sorce_date+3600*24*2)>= $nowTime:
                $temp_time = date('G:i',$sorce_date);
                $timeHtml = '昨天'.$temp_time ;
                break;

            //前天
            case ($sorce_date+3600*24*3)>= $nowTime:
                $temp_time  = date('G:i',$sorce_date);
                $timeHtml = '前天'.$temp_time ;
                break;

            //3天前
            case ($sorce_date+3600*24*4)>= $nowTime:
                $timeHtml = '3天前';
                break;

            default:
				//判断是否是同一年
				if(intval(date('Y',$sorce_date))==intval(date('Y',$nowTime))){
					$timeHtml = date('n月j日',$sorce_date);	
				}else{
					$timeHtml = date('Y年n月j日',$sorce_date);
				}
                break;

        }
        return $timeHtml;

    }
?>