<?php
/**
 * Class RPagerHelper
 *
 * @author: Raysmond, Xiangyan Sun
 */
class RPagerHelper
{
    // Pager id to differentiate pagers in a page
    private $pageId;

    // Total records sum
    private $rowSum;

    // Records sum in a page
    private $rowsInPage;

    // Page sum
    private $pageSum;

    // Page number will be added to the URL
    private $url;

    // Current page
    private $curPage;


    public $pagerText = array(
        'first' => "First",
        'last' => 'Last',
        'prev' => '&laquo;',
        'next' => '&raquo;',
    );

    /**
     * Constructor
     * @param $pageId pager ID to differentiate pagers in a page
     * @param $rowSum total rows
     * @param int $rowsInPage how many rows in a page
     * @param string $url page URL
     * @param int $curPage current page
     */
    public function __construct($pageId, $rowSum, $rowsInPage = 10, $url = '',$curPage=1)
    {
        $this->pageId = trim($pageId);
        $this->rowSum = $rowSum;
        $this->rowsInPage = $rowsInPage;
        $this->url = $url;
        $this->pageSum = ceil($rowSum / $rowsInPage);
        $this->curPage = $curPage;
    }

    /**
     * Show pager
     * @param bool $showPrev whether or not to show previous page link
     * @param bool $showNext whether or not to show next page link
     * @param bool $showFirst whether or not to show the first page link
     * @param bool $showLast whether or not to show the last page link
     * @param int $pagesViewNum number of previous and next page links displayed
     * @return string pager link HTML
     */
    public function showPager($showPrev = true, $showNext = true, $showFirst = true, $showLast = true, $pagesViewNum = 4)
    {
        $pager = '<ul id="pager-'.$this->pageId.'" class="pagination">';
        $isOdd = $pagesViewNum % 2 > 0 ? true : false;
        $curPage = 1;
        if (isset($_GET[$this->pageId]))
            $curPage = $_GET[$this->pageId];

        $appendStr = '?';
        if(strpos($this->url,'?')>0)
            $appendStr = '&&';

        $pageSize = "&&pagesize=".$this->rowsInPage;

        $prevStyle = ($curPage > 1) ? '': ' disabled';
        $nextStyle = ($curPage < $this->pageSum) ? '': ' disabled';

        if ($showFirst) {
            $pager .= '<li class="pager-item' . $prevStyle . '"><a href="' . $this->url . $appendStr . $this->pageId . '=1'.$pageSize.'">' . $this->pagerText['first'] . '</a></li>';
        }
        if ($showPrev) {
            $num = $curPage == 1 ? 1 : ($curPage - 1);
            $pager .= '<li class="pager-item' . $prevStyle . '"><a href="' . $this->url . $appendStr . $this->pageId . '=' . $num.$pageSize . '">' . $this->pagerText['prev'] . '</a></li>';
        }

        $current = $curPage;
        $current = max($pagesViewNum + 1, $current);
        $current = min($this->pageSum - $pagesViewNum, $current);
        $beginPage = max(1, $current - $pagesViewNum);
        $endPage = min(max(1, $this->pageSum), $current + $pagesViewNum);
        if ($beginPage >= $endPage) {
            $beginPage = $endPage = 1;
        }
        if ($beginPage > 1) {
            $pager .= '<li class="pager-item"><a href="' . $this->url . $appendStr . $this->pageId . '=' . ($beginPage - 1).$pageSize . '">...</a></li>';
        }
        for ($i = $beginPage; $i <= $endPage; $i++) {
            $pager .= '<li class="pager-item '.(($i==$this->curPage)?'active':'').'"><a href="' . $this->url . $appendStr . $this->pageId . '=' . ($i).$pageSize . '">' . ($i) . '</a></li>';
        }
        if ($endPage < $this->pageSum) {
            $pager .= '<li class="pager-item"><a href="' . $this->url . $appendStr . $this->pageId . '=' . ($endPage + 1) .$pageSize. '">...</a></li>';
        }

        if ($showNext) {
            $num = ($curPage == $this->pageSum ? $this->pageSum : ($curPage + 1));
            $pager .= '<li class="pager-item' . $nextStyle . '"><a href="' . $this->url . $appendStr . $this->pageId . '=' . $num .$pageSize. '">' . $this->pagerText['next'] . '</a></li>';
        }


        if ($showLast) {
            $pager .= '<li class="pager-item' . $nextStyle . '"><a href="' . $this->url . $appendStr . $this->pageId . '=' . $this->pageSum .$pageSize. '">' . $this->pagerText['last'] . '</a></li>';
        }
        $pager.="</ul>";
        return $pager;
    }
}
