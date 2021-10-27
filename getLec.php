<?php
include 'database.php';
$conn = openConn();
if ($conn->connect_error) {
    die("Connection error");
}

if(isset($_POST['lecNo'], $_POST['courseNo'])) {
    $courseNo = $_POST['courseNo'];
    $lecNo = $_POST['lecNo'];
//    get entire lecture from database and trim whitespace
    $query="SELECT content FROM lectures WHERE course=? AND lecture=?";
    if ($stmt = mysqli_prepare($conn,$query)){
        mysqli_stmt_bind_param($stmt, "ss",$courseNo,$lecNo);
        mysqli_stmt_execute($stmt);
        $stmt->store_result();
        $stmt->bind_result($result);
        $stmt->fetch();
        $lecture = trim($result);
        $lecHtml = "";
        // find tags in string and execute accordingly and cut sting
        $offset = 0;
        $tagStart = "<";
        $tagEnd = ">";
        $closeTagStart = "</";
        $count = 0;
        $imgTextStart = "../shared/Unit" . $lecNo . "images/";
        $imgTextEnd = ".png";
        $imgNo = 1;
        // parse as long as a tag appears
        while (($tagStartPos = strpos($lecture, $tagStart)) !== FALSE) {
            $count++;
            $tagStartPosCurrent = $tagStartPos;
            $tagEndPos = strpos($lecture, $tagEnd);
            $tag = substr($lecture, $tagStartPosCurrent+1, $tagEndPos-1);
            if($tag == "list"){
                $listEndPos = strpos($lecture, $closeTagStart);
                $listCloseTag = "</list>";
                $listEnd = strpos($lecture, $listCloseTag);
                $list = trim(substr($lecture, $tagEndPos+1, $listEnd-strlen($listCloseTag)));
                $lecHtml .= "<ul>";
                // find all items in the list
                $offset = 0;
                $searchItem = "<item>";
                $searchItemEnd = "</item>";
                while (($pos = strpos($list, $searchItem, $offset)) !== FALSE) {
                    $offset   = $pos + strlen($searchItem);
                    $itemEnd = strpos($list, $searchItemEnd);
                    $itemText = substr($list, $pos+strlen($searchItem), $itemEnd-strlen($searchItemEnd)+1);
                    $itemText = str_replace("<", "",$itemText);
                    $lecHtml .= "<li>" .$itemText . "</li>";
                    $list = substr($list, $itemEnd);
                }
                $lecture = trim(substr($lecture, $listEnd+strlen($listCloseTag)));
                $lecHtml .= "</ul>";
            }
            else if($tag == "table"){
                    $lecHtml .= "<table border = '1'>";
                    $tableCloseTag = "</table>";
                    $tableEnd = strpos($lecture, $tableCloseTag);
                    $tableText = trim(substr($lecture, $tagEndPos+1, $tableEnd-strlen($tableCloseTag)));
                    while (($tableTagStartPos = strpos($tableText, $tagStart)) !== FALSE) {
                        $tableItemTagStartPosCurrent = $tableTagStartPos;
                        $tableItemEndTagPos = strpos($tableText, $closeTagStart);
                        $tableTagEndPos = strpos($tableText, $tagEnd);
                        $tableTag = substr($tableText, strpos($tableText, $tagStart)+1, $tableTagEndPos-1);
                        if($tableTag == "caption"){
                            $tableCaptionClosetag = "</caption>";
                            $tableItemText = substr($tableText, $tableTagEndPos+1, $tableItemEndTagPos-strlen($tableCaptionClosetag)+1);
                            $lecHtml .= "<caption><strong>" . $tableItemText . "</strong></caption>";
                            $tableText = trim(substr($tableText,strpos($tableText, $tableCaptionClosetag)+strlen($tableCaptionClosetag)));
                        }
                        else if($tableTag == "header"){
                            $tableheaderOpenTag = "<header>";
                            $tableHeaderClosetag = "</header>";
                            $tableHeaderClosetagPos = strpos($tableText, $tableHeaderClosetag);
                            $headerText = trim(substr($tableText, strlen($tableheaderOpenTag), $tableHeaderClosetagPos-strlen($tableHeaderClosetag)));
                            $headerItemTag = "<item>";
                            $headerItemCloseTag = "</item>";
                            $headerItemCloseTagPos = strpos($headerText, $headerItemCloseTag);
                            $lecHtml .= "<thead><tr>";
                            while (($headerItemTagStartPos = strpos($headerText, $tagStart)) !== FALSE) {
                                $headerItemCloseTagPos = strpos($headerText, $headerItemCloseTag);
                                $headerItemText = trim(substr($headerText, strlen($headerItemTag), $headerItemCloseTagPos-strlen($headerItemCloseTag)+1));
                                $lecHtml .= "<th>" . $headerItemText . "<th>";
                                $headerText = trim(substr($headerText, $headerItemCloseTagPos+strlen($headerItemCloseTag)));
                            }
                            $tableText = trim(substr($tableText,strpos($tableText, $tableHeaderClosetag)+strlen($tableHeaderClosetag)));
                            $lecHtml .= "</thead></tr><tbody>";
                        }
                        else if($tableTag == "row"){
                            $tableRowOpenTag = "<row>";
                            $tableRowCloseTag = "</row>";
                            $tableRowCloseTagPos = strpos($tableText, $tableRowCloseTag);
                            $rowText = trim(substr($tableText, strlen($tableRowOpenTag), $tableRowCloseTagPos-strlen($tableRowCloseTag)));
                            $rowItemTag = "<item>";
                            $rowItemCloseTag = "</item>";
                            $rowItemCloseTagPos = strpos($rowText, $rowItemCloseTag);
                            $lecHtml .= "<tr>";
                            while (($rowItemTagStartPos = strpos($rowText, $tagStart)) !== FALSE) {
                                $rowItemCloseTagPos = strpos($rowText, $rowItemCloseTag);
                                $rowItemText = trim(substr($rowText, strlen($rowItemTag), $rowItemCloseTagPos-strlen($rowItemCloseTag)+1));
                                $rowItemText = str_replace("<", "&lt",$rowItemText);
                                $rowItemText = str_replace(">", "&gt",$rowItemText);
                                $lecHtml .= "<td>" . $rowItemText . "<td>";
                                $rowText = trim(substr($rowText, $rowItemCloseTagPos+strlen($rowItemCloseTag)));

                            }
                            $tableText = trim(substr($tableText,strpos($tableText, $tableRowCloseTag)+strlen($tableRowCloseTag)));
                            $lecHtml .= "</tr>";
                        }
                    }
                    $lecHtml .= $tableCloseTag;
                    $lecture = trim(substr($lecture, $tableEnd+strlen($tableCloseTag)));
                }
                else if($tag == "Title"){
                    $titleCloseTag = "</Title>";
                    $titleEnd = strpos($lecture, $titleCloseTag);
                    $title = substr($lecture, $tagEndPos+1, $titleEnd-strlen($titleCloseTag)+1);
                    $lecHtml .= "<h3>" . $title . "</h3>";
                    $lecture = trim(substr($lecture, $titleEnd + strlen($titleCloseTag)));
                }
                else if($tag == "Title1"){
                    $titleCloseTag = "</Title1>";
                    $titleEnd = strpos($lecture, $titleCloseTag);
                    $title = substr($lecture, $tagEndPos+1, $titleEnd-strlen($titleCloseTag)+1);
                    $lecHtml .= "<h4>" . $title . "</h4>";
                    $lecture = trim(substr($lecture, $titleEnd + strlen($titleCloseTag)));
                }
                else if($tag == "Title2"){
                    $titleCloseTag = "</Title2>";
                    $titleEnd = strpos($lecture, $titleCloseTag);
                    $title = substr($lecture, $tagEndPos+1, $titleEnd-strlen($titleCloseTag)+1);
                    $lecHtml .= "<h5>" . $title . "</h5>";
                    $lecture = trim(substr($lecture, $titleEnd + strlen($titleCloseTag)));
                }
                else if($tag == "text"){
                    $textCloseTag = "</text>";
                    $textEnd = strpos($lecture, $textCloseTag);
                    $text = substr($lecture, $tagEndPos+1, $textEnd-strlen($textCloseTag)+1);
                    $lecHtml .= "<p>" . $text . "</p>";
                    $lecture = trim(substr($lecture, $textEnd + strlen($textCloseTag)));
                }
                else if($tag == "img"){
                    $lecHtml .= "<img img src =" . $imgTextStart . $imgNo . $imgTextEnd . ">";
                    $imgNo++;
                    $lecture = trim(substr($lecture, $tagEndPos+1));
                }
                else if($tag == "caption"){
                    $captionCloseTag = "</caption>";
                    $captionEnd = strpos($lecture, $captionCloseTag);
                    $caption = substr($lecture, $tagEndPos+1, $captionEnd-strlen($captionCloseTag)+1);
                    $lecHtml .= "<figcaption>" . $caption . "</figcaption>";
                    $lecture = trim(substr($lecture, $captionEnd + strlen($captionCloseTag)));
                }
                else if($tag == "newline"){
                    $lecHtml .= "<br>";
                    $lecture = trim(substr($lecture, $tagEndPos+1));
                }
                else if($tag == "item"){
                    $itemTag = "<item>";
                    $itemCloseTag = "</item>";
                    $itemCloseTagPos = strpos($lecture, $itemCloseTag);
                    $item = substr($lecture, $tagEndPos+1, $itemCloseTagPos-strlen($itemCloseTag));
                    $lecHtml .= "<li>" . $item . "</li>";
                    $lecture = trim(substr($lecture, $itemCloseTagPos + strlen($itemCloseTag)));
                }
        }
        echo $lecHtml;
    }
}