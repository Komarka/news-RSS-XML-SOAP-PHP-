<?php
interface I_news{
function saveNews($title,$category,$description,$source);
function getNews();
function deleteNews($id);
function clearStr($data);
function clearInt($data);
}
?>
