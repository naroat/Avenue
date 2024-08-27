<?php


namespace App\Package;

use Hyperf\Di\Annotation\Inject;
use Hyperf\HttpServer\Contract\RequestInterface;
use Hyperf\Validation\Contract\ValidatorFactoryInterface;

class Verify
{
    #[Inject]
    protected ValidatorFactoryInterface $validationFactory;

    /**
     * 验证
     *
     * @param $rule
     * @param $rule_msg
     * @param RequestInterface $request
     * @return mixed
     * @throws \Exception
     */
    public function check($params, $rule, $rule_msg)
    {
        $validator = $this->validationFactory->make(
            $params,
            $rule,
            $rule_msg
        );
        if ($validator->fails()) {
            throw new \Exception($validator->errors()->first());
        }

        return true;
    }

    /**
     * 过滤参数
     *
     * @param $params
     * @param RequestInterface $request
     * @param bool $suffix
     * @return array
     */
    public function requestParams($params, RequestInterface $request, $suffix = false): array
    {
        $p = [];
        $i = 0;
        foreach ($params as $param) {
            if (! is_array($param)) {
                $p[$suffix == true ? $i++ : $param] = $request->input($param);
            } else {
                if (! isset($param[1])) {
                    $param[1] = null;
                }
                if (! isset($param[2])) {
                    $param[2] = '';
                }
                if (is_array($param[0])) {
                    $name = is_array($param[1]) ? $param[0][0] . '/a' : $param[0][0] . '/' . $param[0][1];
                    $keyName = $param[0][0];
                } else {
                    $name = is_array($param[1]) ? $param[0] . '/a' : $param[0];
                    $keyName = $param[0];
                }
                $p[$suffix == true ? $i++ : (isset($param[3]) ? $param[3] : $keyName)] = $request->input($name, $param[1], $param[2]);
            }
        }
        return $p;
    }
}