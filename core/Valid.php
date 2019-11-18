<?php
class Core_Valid extends Core_Base {

        //$key为身份证号码前两位,为string类型
        static $vcity = array(
                '11' =>"北京", '12' =>"天津", '13' =>"河北", '14' =>"山西", '15' =>"内蒙古",
                '21' =>"辽宁", '22' =>"吉林", '23' =>"黑龙江", '31' =>"上海", '32' =>"江苏",
                '33' =>"浙江", '34' =>"安徽", '35' =>"福建", '36' =>"江西", '37' =>"山东", '41' =>"河南",
                '42' =>"湖北", '43' =>"湖南", '44' =>"广东", '45' =>"广西", '46' =>"海南", '50' =>"重庆",
                '51' =>"四川", '52' =>"贵州", '53' =>"云南", '54' =>"西藏", '61' =>"陕西", '62' =>"甘肃",
                '63' =>"青海", '64' =>"宁夏", '65' =>"新疆", '71' =>"台湾", '81' =>"香港", '82' =>"澳门", '91' =>"国外"
            );

        function isIDCard($card) {
            $card = strtoupper(trim($card));
            //是否为空
            if ($card === '') {
                return "不可为空";
            }

            //校验长度，类型
            if ($this->isIDCardNo($card) === false) {
                return "长度不对";
            }

            //检查省份
            if ($this->checkIDCardProvince($card) === false) {
                return "所在省份不对";
            }


            //校验位的检测
            if ($this->checkIDCardParity($card) === false) {
                return "号码校验位不对";
            }

            return true;
        }

        //检查号码是否符合规范，包括长度，类型
        function isIDCardNo($card) {
            $reg = '/(^\d{15})|(^\d{17}(\d|X))$/';
            $matches = array();
            if (preg_match($reg, $card, $matches)) {
                //var_dump($matches);
                return true;
            } else {
                return false;
            }
        }
        //取身份证前两位,校验省份
        function checkIDCardProvince($card) {
            $province = substr($card, 0, 2); //var_dump(self::$vcity); var_dump($province); exit;
            if (array_key_exists($province, self::$vcity)) {
                return true;
            } else {
                return false;
            }
        }

        //检查生日是否正确
        function checkIDCardBirthday($card) {
            $len = strlen($card);

            //身份证15位时，次序为省（3位）市（3位）年（2位）月（2位）日（2位）校验位（3位），皆为数字
            if ($len == 15) {
                $re_fifteen = '/^(\d{6})(\d{2})(\d{2})(\d{2})(\d{3})/';
                $arr_data = array();
                preg_match($re_fifteen, $card, $arr_data);
                $year = $arr_data[1];
                $month = $arr_data[2];
                $day = $arr_data[3];
                $birthday = date('Y-m-d',strtotime('19'.$year.'-'.$month.'-'.$day));
                return $this->verifyIDCardBirthday('19'.$year, $month, $day, $birthday);
            }

            //身份证18位时，次序为省（3位）市（3位）年（4位）月（2位）日（2位）校验位（4位），校验位末尾可能为X
            if ($len == 18) {
                $re_eighteen = '/^(\d{6})(\d{4})(\d{2})(\d{2})(\d{3})([0-9]|X)/';
                $arr_data = array();
                preg_match($re_fifteen, $card, $arr_data);
                $year = $arr_data[1];
                $month = $arr_data[2];
                $day = $arr_data[3];
                $birthday = date('Y-m-d',strtotime($year.'-'.$month.'-'.$day));
                return $this->verifyIDCardBirthday($year, $month, $day, $birthday);
            }
            return false;
        }

        //可以暂时不用
        function verifyIDCardBirthday($year,$month,$day,$birthday) {
            //主要是验证身份证年龄到当前在3-100岁之间，月份在01-12之间，日期在01-31之间
        }

        function checkIDCardParity($card) {
            //15位转18位
            $card = $this->changeIDCardFivteenToEighteen($card);
            $len = strlen($card);
            if ($len == 18) {
                $arrInt = array(7, 9, 10, 5, 8, 4, 2, 1, 6, 3, 7, 9, 10, 5, 8, 4, 2);
                $arrCh = array('1', '0', 'X', '9', '8', '7', '6', '5', '4', '3', '2');
                $cardTemp = 0;
                for($i = 0; $i < 17; $i++) {
                    $cardTemp += substr($card,$i, 1) * $arrInt[$i];
                }
                $valnum = $arrCh[$cardTemp % 11];
                if ($valnum == substr($card,17, 1)) {
                    return true;
                }
                return false;
            }
            return false;
        }

        //将15位号码转为18位,转换规则是
        function changeIDCardFivteenToEighteen($card) {
            if (strlen($card) == 15) {
                $arrInt = array(7, 9, 10, 5, 8, 4, 2, 1, 6, 3, 7, 9, 10, 5, 8, 4, 2);
                $arrCh = array('1', '0', 'X', '9', '8', '7', '6', '5', '4', '3', '2');
                $cardTemp = 0;
                $card = substr($card, 0, 6) .'19'. substr($card, 6, 9); //缺少校验位
                for($i = 0; $i < 17; $i++) {
                    $cardTemp += substr($card,$i, 1) * $arrInt[$i];
                }
                $card .= $arrCh[$cardTemp % 11];
                return $card;
            }
            return $card;
        }


        /*
        -----------------------------------------------------------
        函数名称：isEmpty
        简要描述：检查输入的数组键中是否含空
        输入：array, array
        输出：boolean
        修改日志：------
        -----------------------------------------------------------
        */
        function isEmpty($data, $keys)
        {
            foreach ($keys as $value) {
                if (empty($data[$value])) {
                    return true;
                }
            }
            return false;
        }
        /*
        -----------------------------------------------------------
        函数名称：isNumber
        简要描述：检查输入的是否为数字
        输入：string
        输出：boolean
        修改日志：------
        -----------------------------------------------------------
        */
        function isNumber($val)
        {
            if(@preg_match("/^[0-9]+$/", $val))
                return true;
            return false;
        }

        /*
        -----------------------------------------------------------
        函数名称：isPhone
        简要描述：检查输入的是否为电话
        输入：string
        输出：boolean
        修改日志：------
        -----------------------------------------------------------
        */
        function isPhone($val)
        {
            //eg: xxx-xxxxxxxx-xxx | xxxx-xxxxxxx-xxx ...
            if(@preg_match("/^((0\d{2,3})-)(\d{7,8})(-(\d{3,}))?$/",$val))
                return true;
            return false;
        }

        /*
        -----------------------------------------------------------
        函数名称：isMobile
        简要描述：检查输入的是否为手机号
        输入：string
        输出：boolean
        修改日志：------
        -----------------------------------------------------------
        */
        function isMobile($val)
        {
            //该表达式可以验证那些不小心把连接符“-”写出“－”的或者下划线“_”的等等
            if(preg_match("/1[34578]{1}\d{9}$/", $val))
                return true;
            return false;
        }

        /*
        -----------------------------------------------------------
        函数名称：isPostcode
        简要描述：检查输入的是否为邮编
        输入：string
        输出：boolean
        修改日志：------
        -----------------------------------------------------------
        */
        function isPostcode($val)
        {
            if(@preg_match("/^[0-9]{4,6}$/",$val))
                return true;
            return false;
        }

        /*
        -----------------------------------------------------------
        函数名称：isEmail
        简要描述：邮箱地址合法性检查
        输入：string
        输出：boolean
        修改日志：------
        -----------------------------------------------------------
        */
        function isEmail($val,$domain="")
        {
            if(!$domain)
            {
                if( preg_match("/^[a-z0-9\-\_\.]+@[\da-z][\.\w-]+\.[a-z]{2,4}$/i", $val) )
                {
                    return true;
                }
                else
                    return false;
            }
            else
            {
                if( preg_match("/^[a-z0-9\-\_\.]+@".$domain."$/i", $val) )
                {
                    return true;
                }
                else
                    return false;
            }
        }//end func

        /*
        -----------------------------------------------------------
        函数名称：isName
       简要描述：姓名昵称合法性检查，只能输入中文英文
        输入：string
        输出：boolean
        修改日志：------
        -----------------------------------------------------------
        */
        function isName($val)
        {
            if( preg_match("/^[\x80-\xffa-zA-Z0-9]{3,60}$/", $val) )//2008-7-24
            {
                return true;
            }
            return false;
        }//end func

        /*
        -----------------------------------------------------------
        函数名称:isDomain($Domain)
        简要描述:检查一个（英文）域名是否合法
        输入:string 域名
        输出:boolean
        修改日志:------
        -----------------------------------------------------------
        */
        function isDomain($Domain)
        {
            if(!preg_match("/^[0-9a-z]+[0-9a-z\.-]+[0-9a-z]+$/i", $Domain))
            {
                return false;
            }
            if(!preg_match("/\./i", $Domain))
            {
                return false;
            }

            if(preg_match("/\-\./i", $Domain) | preg_match("/\-\-/i", $Domain) | preg_match("/\.\./i", $Domain) | preg_match("/\.\-/i", $Domain))
            {
                return false;
            }

            $aDomain= explode(".",$Domain);
            if( !preg_match("/[a-zA-Z]/",$aDomain[count($aDomain)-1]) )
            {
                return false;
            }

            if(strlen($aDomain[0]) > 63 || strlen($aDomain[0]) < 1)
            {
                return false;
            }
            return true;
        }

        /*
        -----------------------------------------------------------
        函数名称:isNumberLength($theelement, $min, $max)
        简要描述:检查字符串长度是否符合要求
        输入:mixed (字符串，最小长度，最大长度)
        输出:boolean
        修改日志:------
        -----------------------------------------------------------
        */
        function isNumLength($val, $min, $max)
        {
            $theelement= trim($val);
            if(@preg_match("/^[0-9]{".$min.",".$max."}$/",$val))
                return true;
            return false;
        }

        /*
        -----------------------------------------------------------
        函数名称:isNumberLength($theelement, $min, $max)
        简要描述:检查字符串长度是否符合要求
        输入:mixed (字符串，最小长度，最大长度)
        输出:boolean
        修改日志:------
        -----------------------------------------------------------
        */
        function isEngLength($val, $min, $max)
        {
            $theelement= trim($val);
            if(@preg_match("/^[a-zA-Z]{".$min.",".$max."}$/",$val))
                return true;
            return false;
        }

        function isPasswordLength($val, $min, $max)
        {
            $theelement= trim($val);
            if(@preg_match("/^[0-9a-zA-Z\/\*\-]{".$min.",".$max."}$/",$val))
                return true;
            return false;
        }

        function isAccountLength($val, $min, $max)
        {
            $theelement= trim($val);
            if(@preg_match("/^[0-9a-zA-Zx00-\xff]{".$min.",".$max."}$/",$val))
                return true;
            return false;
        }

        /*
        -----------------------------------------------------------
        函数名称：isEnglish
        简要描述：检查输入是否为英文
        输入：string
       输出：boolean
        作者：------
        修改日志：------
        -----------------------------------------------------------
        */
        function isEnglish($theelement)
        {
            if( @preg_match("/[\x80-\xff]./",$theelement) )
            {
                return false;
            }
            return true;
        }

        /*
        -----------------------------------------------------------
        函数名称：isChinese
        简要描述：检查是否输入为汉字
        输入：string
        输出：boolean
        修改日志：------
        -----------------------------------------------------------
        */
        function isChinese($sInBuf)
        {
            $iLen= strlen($sInBuf);
            for($i= 0; $i< $iLen; $i++)
            {
                if(ord($sInBuf{$i})>=0x80)
                {
                    if( (ord($sInBuf{$i})>=0x81 && ord($sInBuf{$i})<=0xFE) && ((ord($sInBuf{$i+1})>=0x40 && ord($sInBuf{$i+1}) < 0x7E) || (ord($sInBuf{$i+1}) > 0x7E && ord($sInBuf{$i+1})<=0xFE)) )
                    {
                        if(ord($sInBuf{$i})>0xA0 && ord($sInBuf{$i})<0xAA)
                        {
                            //有中文标点
                            return false;
                        }
                    }
                    else
                    {
                        //有日文或其它文字
                        return false;
                    }
                    $i++;
                }
                else
                {
                    return false;
                }
            }
            return true;
        }

        /*
        -----------------------------------------------------------
        函数名称：isDate
        简要描述：检查日期是否符合0000-00-00
        输入：string
        输出：boolean
        修改日志：------
        -----------------------------------------------------------
        */
        function isDate($sDate)
        {
            if( @preg_match("/^[0-9]{4}\-[][0-9]{2}\-[0-9]{2}$/",$sDate) )
            {
                return true;
            }
            else
            {
                return false;
            }
        }
        /*
        -----------------------------------------------------------
        函数名称：isTime
        简要描述：检查日期是否符合0000-00-00 00:00:00
        输入：string
        输出：boolean
        修改日志：------
        -----------------------------------------------------------
        */
        function isTime($sTime)
        {
            if( @preg_match("/^[0-9]{4}\-[][0-9]{2}\-[0-9]{2} [0-9]{2}:[0-9]{2}:[0-9]{2}$/",$sTime) )
            {
                return true;
            }
            else
            {
                return false;
            }
        }

        /*
        -----------------------------------------------------------
        函数名称:isMoney($val)
        简要描述:检查输入值是否为合法人民币格式
        输入:string
        输出:boolean
        修改日志:------
        -----------------------------------------------------------
        */
        function isMoney($val)
        {
            if(@preg_match("/^[0-9]{1,}$/", $val))
                return true;
            if( @preg_match("/^[0-9]{1,}\.[0-9]{1,2}$/", $val) )
                return true;
            return false;
        }

        /*
        -----------------------------------------------------------
        函数名称:isIp($val)
        简要描述:检查输入IP是否符合要求
        输入:string
       输出:boolean
        修改日志:------
        -----------------------------------------------------------
        */
        function isIp($val)
        {
            return (bool) ip2long($val);
        }
    //-----------------------------------------------------------------------------
}