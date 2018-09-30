<?php

namespace App\Tool;


class ToolBase
{

    /**
     * 返回数据
     *
     * @param int $status
     * @param string $info
     * @param array $data
     * @return json
     * @author newton
     * @date 2018/7/5
     **/
    public function returnJson($status = 1, $info = '', $data = [])
    {
        $data = [
            'status' => $status,
            'info'   => $info,
            'data'   => $data,
        ];
        return response()->json($data);
    }
    

    /**
     * 对象转数组
     *
     * @param object $obj
     * @return array
     * @author newton <yushuainiu@51talk.com>
     * @date 2018/8/9
     **/
    public static function objToArray($obj)
    {
        return json_decode(json_encode($obj), true);
    }


    /**
     * 得到远程地址的内容
     *
     * @param string $url
     * @param array $params
     * @param string $method
     * @return array
     */
    public static function curlHttp($url = '', $params = array(), $method = 'GET')
    {
        $postData   = $params;
        $curlMethod = ($method == 'GET') ? CURLOPT_HTTPGET : CURLOPT_POST;

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, $curlMethod, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $postData);
        curl_setopt($ch, CURLOPT_HEADER, 0);
        $content = curl_exec($ch);
        curl_close($ch);

        return $content;
    }


    /**
     * 记录日志
     *
     * <pre>
     *  string class
     *  string function
     *  string message
     * </pre>
     * @param array $params
     * @return array
     * @author newton <yushuainiu@51talk.com>
     * @date 2018/9/7
     **/
    public static function writeLog($params = array())
    {
        if (empty($params['message'])) {
            return false;
        }
        $params['date'] = date('Y-m-d H:i:s');

        $message = json_encode($params) . PHP_EOL;
        $logFile = config('app.log_path');

        file_put_contents($logFile, $message, FILE_APPEND);

        return true;
    }
}
