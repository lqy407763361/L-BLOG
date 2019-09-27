<?php
/**
 * L-BLOG
 *
 * @author  lqy407763361
 * @github  https://github.com/lqy407763361
 * @码云  https://gitee.com/lqy407763361
 */

namespace page;

use think\Paginator;

class IndexPage extends Paginator{
    //首页
    protected function getFirstButton($text = '首页'){
        if($this->currentPage() <= 1){
            return $this->getDisabledTextWrapper($text);
        }
        $url = $this->url(1);

        return $this->getPageLinkWrapper($url, $text);
    }

    //尾页
    protected function getLastButton($text = '尾页'){
        if(!$this->hasMore){
            return $this->getDisabledTextWrapper($text);
        }
        $url = $this->url($this->lastPage);

        return $this->getPageLinkWrapper($url, $text);
    }

    //上一页
    protected function getPreviousButton($text = "上一页"){
        if($this->currentPage() <= 1){
            return $this->getDisabledTextWrapper($text);
        }
        $url = $this->url($this->currentPage() - 1);

        return $this->getPageLinkWrapper($url, $text);
    }

    //下一页
    protected function getNextButton($text = '下一页'){
        if (!$this->hasMore) {
            return $this->getDisabledTextWrapper($text);
        }
        $url = $this->url($this->currentPage() + 1);

        return $this->getPageLinkWrapper($url, $text);
    }


    //页码按钮
    protected function getLinks(){
        if ($this->simple) {
            return '';
        }

        $block = [
            'first'  => null,
            'slider' => null,
            'last'   => null,
        ];

        $side   = 3;
        $window = $side * 2;

        if ($this->lastPage < $window + 6) {
            $block['first'] = $this->getUrlRange(1, $this->lastPage);
        } elseif ($this->currentPage <= $window) {
            $block['first'] = $this->getUrlRange(1, $window + 2);
            $block['last']  = $this->getUrlRange($this->lastPage - 1, $this->lastPage);
        } elseif ($this->currentPage > ($this->lastPage - $window)) {
            $block['first'] = $this->getUrlRange(1, 2);
            $block['last']  = $this->getUrlRange($this->lastPage - ($window + 2), $this->lastPage);
        } else {
            $block['first']  = $this->getUrlRange(1, 2);
            $block['slider'] = $this->getUrlRange($this->currentPage - $side, $this->currentPage + $side);
            $block['last']   = $this->getUrlRange($this->lastPage - 1, $this->lastPage);
        }

        $html = '';

        if (is_array($block['first'])) {
            $html .= $this->getUrlLinks($block['first']);
        }

        if (is_array($block['slider'])) {
            $html .= $this->getDots();
            $html .= $this->getUrlLinks($block['slider']);
        }

        if (is_array($block['last'])) {
            $html .= $this->getDots();
            $html .= $this->getUrlLinks($block['last']);
        }

        return $html;
    }

    //渲染分页html
    public function render(){
        if ($this->hasPages()){
            if($this->simple){
                return sprintf(
                    '<ul class="page">%s %s</ul>',
                    $this->getPreviousButton(),
                    $this->getNextButton()
                );
            }else{
                return sprintf(
                    '<ul class="page">%s %s %s %s %s</ul>',
                    $this->getFirstButton(),
                    $this->getPreviousButton(),
                    $this->getLinks(),
                    $this->getNextButton(),
                    $this->getLastButton()
                );
            }
        }
    }

    //生成一个可点击的按钮
    protected function getAvailablePageWrapper($url, $page){
        return '<li><a href="' . htmlentities($url) . '">' . $page . '</a></li>';
    }

    //生成一个禁用的按钮
    protected function getDisabledTextWrapper($text){
        return '<li class="disabled">' . $text . '</li>';
    }

    //生成一个激活的按钮
    protected function getActivePageWrapper($text){
        return '<li class="active">' . $text . '</li>';
    }

    //生成省略号按钮
    protected function getDots(){
        return $this->getDisabledTextWrapper('...');
    }

    //批量生成页码按钮.
    protected function getUrlLinks(array $urls){
        $html = '';

        foreach ($urls as $page => $url) {
            $html .= $this->getPageLinkWrapper($url, $page);
        }

        return $html;
    }

    //生成普通页码按钮
    protected function getPageLinkWrapper($url, $page){
        if($this->currentPage() == $page){
            return $this->getActivePageWrapper($page);
        }

        return $this->getAvailablePageWrapper($url, $page);
    }
}
