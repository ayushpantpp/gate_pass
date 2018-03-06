<?php

class Paging {

	function pagesDiv($limit,$count,$pageNo=1){
		$root_url = '';
		$totalPages = ceil($count/$limit);
		$url = $this->checkUrl();
		$html = '<ul class="paginate pag1 clearfix" >';
		$html .= '<li class="single">Page ' . $pageNo . ' of ' . $totalPages . '</li>';
		if($pageNo != 1) {
			$prevlink = "summary.php?page=" . ($pageNo-1) . $url;
			$html .= ' <li><a href="' . $prevlink . '">Prev</a></li>';
		}
		if($pageNo>5){
			$leftLimit = $pageNo - 5;
			$rightLimit = $pageNo + 5;
		}
		else {
			$leftLimit = 1;
			$rightLimit = 10;
		}
		if($rightLimit > $totalPages)
			$rightLimit = $totalPages;
		
		for($page=$leftLimit; $page<=$rightLimit; $page++){
			$link = "summary.php?page=" . $page . $url;
			if($page == $pageNo)
				$html .= '<li class="current">' . $page . '</li>';
			else 
				$html .= '<li><a href="' . $link . '">' . $page . '</a></li>';
		}
		if($pageNo < $totalPages){
			$nextlink = "summary.php?page=" . ($pageNo+1) . $url;
			$html .= '<li><a href="' . $nextlink . '">Next</a></li>';
		}
		else if($totalPages != 1 && $pageNo != $totalPages)
			$html .= ' <li><a href="#">Next</a></li>';
		$html .= '</ul>';
		return $html;
	}

	function checkUrl(){
		$url = '';
		foreach($_GET as $k=>$val){
			if($k!='page')
				$url .= '&'.$k.'='.$val;
		}
		return $url;
	}
}
