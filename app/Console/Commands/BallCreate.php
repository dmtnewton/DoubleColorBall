<?php
/**
 * 创建球号
 * @author newton
 * @date 2018/8/2
 * @run php /work/www/program/artisan ball:create 'This is first argument.'
 **/

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
//use App\Models\BallModel;
use App\Models\Ball\DoubleBallAllModel;

class BallCreate extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'ball:create {test}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create all ball code.';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        //
//        $test = $this->argument('test');
//        echo 'argv:<pre>' . PHP_EOL;
//        print_r($test);
//        echo '</pre>' . PHP_EOL;

        ini_set("display_errors", true);
        error_reporting(E_ALL);

        set_time_limit(0);
        ini_set('max_execution_time', 0);
        ini_set('memory_limit', '868M');

        $this->info(">>> START..." . PHP_EOL);

        $startTime   = microtime(true);
        $redBallArr  = range(1, 33);
        $blueBallArr = range(1, 16);
        $number      = 0;
        $limit       = 1000;
        $dataBlock   = [];
        $total       = ceil(17720976 / $limit);

        //进度条
        $bar = $this->output->createProgressBar($total);

//        foreach ($redBallArr as $redCode1) {
        for ($i = 26; $i >= 1; $i--) {
            $redCode1 = $i;
//            echo "-1- {$redCode1}" . PHP_EOL;

            foreach ($redBallArr as $redCode2) {
                if ($redCode1 >= $redCode2) {
                    continue;
                }
//                echo "-2- {$redCode2}" . PHP_EOL;
                foreach ($redBallArr as $redCode3) {
                    if ($redCode2 >= $redCode3) {
                        continue;
                    }
//                    echo "-3- {$redCode3}" . PHP_EOL;
                    foreach ($redBallArr as $redCode4) {
                        if ($redCode3 >= $redCode4) {
                            continue;
                        }
//                        echo "-4- {$redCode4}" . PHP_EOL;
                        foreach ($redBallArr as $redCode5) {
                            if ($redCode4 >= $redCode5) {
                                continue;
                            }
//                            echo "-5- {$redCode5}" . PHP_EOL;
                            foreach ($redBallArr as $redCode6) {
                                if ($redCode5 >= $redCode6) {
                                    continue;
                                }
//                                echo "-6- {$redCode6}" . PHP_EOL;
                                foreach ($blueBallArr as $blueCode) {
//                                    echo "-7- {$blueCode}" . PHP_EOL;

                                    $info = PHP_EOL . ">>> {$redCode1} {$redCode2} {$redCode3} {$redCode4} {$redCode5} {$redCode6} {$blueCode}" . PHP_EOL;
                                    $this->info($info);

                                    $dataArr     = [
                                        'r1' => $redCode1,
                                        'r2' => $redCode2,
                                        'r3' => $redCode3,
                                        'r4' => $redCode4,
                                        'r5' => $redCode5,
                                        'r6' => $redCode6,
                                        'b1' => $blueCode
                                    ];
                                    $dataBlock[] = self::_dealData($dataArr);

                                    $number++;
                                    if ($number % $limit == 0) {
//                                        (new BallModel())->multiInsertData($dataBlock);
                                        (new DoubleBallAllModel())->multiInsertData($dataBlock);
                                        //进度条实时进度
                                        $bar->advance();
                                        $spent     = self::_spentTime($startTime);
                                        $dataBlock = [];
                                        $this->info(">>> Doing : {$number}, spent : {$spent} s" . PHP_EOL);
                                    }
                                }
                            }
                        }
                    }
                }

            }
        }

        if (!empty($dataBlock)) {
//            (new BallModel())->multiInsertData($dataBlock);
            (new DoubleBallAllModel())->multiInsertData($dataBlock);
            $bar->finish();
            $spent = self::_spentTime($startTime);
            $this->info(">>> Done : {$number}, spent : {$spent} s" . PHP_EOL);
        }
        $this->info(">>> END." . PHP_EOL);
    }


    /**
     * 耗时
     *
     * @param int $startTime
     * @return int
     * @author newton <yushuainiu@51talk.com>
     * @date 2018/8/2
     **/
    private static function _spentTime($startTime = 0)
    {
        $endTime = microtime(true);
        $spent   = round(($endTime - $startTime), 6);   //us->ms->s

        return $spent;
    }


    /**
     * 数据处理
     *
     * @param array $data
     * @return array
     * @author newton <yushuainiu@51talk.com>
     * @date 2018/8/2
     **/
    private static function _dealData($data = array())
    {
        if (empty($data)) {
            echo ">>> ERROR: _dealData : empty data." . PHP_EOL;
            return false;
        }
        $orgArr             = $data;
        $weightArr          = self::_getNumberWeightLen($orgArr);
        $data['number']     = implode(',', $orgArr);
        $data['weight']     = $weightArr['weight'];
        $data['weight_num'] = $weightArr['weightNum'];
        $data['sum']        = array_sum($orgArr);
        $data['product']    = 0;//self::_getTotalProduct($orgArr);
        $data['avg']        = round(array_sum($orgArr) / count($orgArr), 2);
        $data['max']        = max($orgArr);
        $data['min']        = min($orgArr);
        $data['poor']       = $data['max'] - $data['min'];
        $data['code']       = 0;
        $data['date']       = '';

        return $data;
    }


    /**
     * 连号位数
     *
     * @param array $data
     * @return array
     * @author newton <yushuainiu@51talk.com>
     * @date 2018/8/2
     **/
    private static function _getNumberWeightLen($data = array())
    {
        if (empty($data)) {
            echo ">>> ERROR: _getNumberWeightLen : empty data." . PHP_EOL;
            return false;
        }
        $len    = count($data);
        $num    = 1;
        $tmpArr = [];
        $data   = array_values($data);
        foreach ($data as $k => $v) {
            if (isset($data[$k + 1]) && ($len >= $k + 1) && ($data[$k] + 1 == $data[$k + 1])) {
                $tmpArr[$k]     = $num;
                $tmpArr[$k + 1] = $num;
            } else {
                $num++;
            }
        }

        $res              = [];
        $res['weight']    = empty($tmpArr) ? 0 : max(array_count_values($tmpArr));
        $res['weightNum'] = empty($tmpArr) ? 0 : count(array_unique($tmpArr));

        return $res;
    }


    /**
     * 数据总积
     *
     * @param array $data
     * @return array
     * @author newton <yushuainiu@51talk.com>
     * @date 2018/8/2
     **/
    private static function _getTotalProduct($data = array())
    {
        $num = 1;
        foreach ($data as $k => $v) {
            $num = $num * $v;
        }

        if (strlen($num) > 10) {
            $num = ceil($num / 10000);
        }

        return $num;
    }


}
