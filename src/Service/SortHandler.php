<?php


namespace App\Service;

use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;
use Symfony\Component\HttpFoundation\Request;

class SortHandler implements SortConst
{
    private ParameterBagInterface $parameterBag;
    private array $sortList = [self::SORT_RANK, self::SORT_PRICE, self::SORT_COMMENT, self::SORT_NEW];
    private Request $request;

    /**
     * SortHandler constructor.
     */
    public function __construct(ParameterBagInterface $parameterBag)
    {
        $this->parameterBag = $parameterBag;
    }

    public function handler(Request $request): array
    {
        $this->request = $request;
        return $this->parameterBag->get('sort.multiply')
            ? $this->parseMulti()
            : $this->parseOne();
    }

    private function parseMulti(): array
    {
        $result = [];
        foreach ($this->sortList as $sort) {
            $data = $this->request->query->get($sort) ?? $this->request->cookies->get($sort) ?? null;
            $this->writeData($result, $data, $sort);
        }
        return $result;
    }

    private function parseOne(): array
    {
        $result = [];
        $getGotten = false;
        foreach ($this->sortList as $sort) {
            $data = $this->request->query->get($sort) ?? null;
            if ($data) {
                $this->writeData($result, $data, $sort);
                $getGotten = true;
            } else {
                $result[$sort] = self::EMPTY;
            }
        }
        if ($getGotten) return $result;
        $result = [];
        foreach ($this->sortList as $sort) {
            $data = $this->request->cookies->get($sort) ?? null;
            $this->writeData($result, $data, $sort);
        }
        return $result;

    }

    private function writeData(array &$array, ?string $data, string $sort)
    {
        if ($data) {
            $order = mb_strtolower(preg_replace('|a-Z|', '', $data));
            $array[$sort] = $this->switchOrder($order);
        }
    }

    private function switchOrder($order): string
    {
        switch ($order) {
            case self::ASC:
                return self::ASC;
            case self::DESC:
                return self::DESC;
            default:
                return self::EMPTY;
        }
    }
}