<?php
//Require the config
require_once("inc/config.inc.php");

//Require entities
require_once("inc/Entities/User.class.php");

require_once("inc/Entities/Review.class.php");

require_once("inc/Entities/Movie.class.php");

//Require Utilities
require_once("inc/Utility/TheMovieDbApi.class.php");
require_once("inc/Utility/Page.class.php");
require_once("inc/Utility/PDOAgent.class.php");
require_once("inc/Utility/UserMapper.class.php");
require_once("inc/Utility/ReviewMapper.class.php");
require_once("inc/Utility/MovieMapper.class.php");
require_once("inc/Utility/LoginManager.class.php");
require_once("inc/Utility/Validation.class.php");

session_start();

if(!LoginManager::verifyLogin()) return;

$movieId = $_GET['movie'];
if(!is_numeric($movieId)) {
    header('Location: home');
    return;
}
$movie = TheMovieDbApi::getMovieById((int)$movieId);

MovieMapper::inizialize("Movie");
ReviewMapper::inizialize("Review");
if(!empty($_POST)){
        $newreview = new Review();
        $newreview->setUserID($_SESSION["user"]->getUserID());
        $newreview->setMovieID(MovieMapper::getMovie($_GET['movie'])->getMovieID());
        $newreview->setReviewDesc($_POST["reviewdesc"]);
        $newreview->setRating($_POST["rating"]);
        var_dump($newreview);
        //ReviewMapper::updateReview($newreview);
}

$_SESSION["MOVIE"] = $movie;
Page::$title = $_SESSION["MOVIE"]->title;
Page::header();

Page::ShowMovie($_SESSION["MOVIE"]);
$Review = new Review();
$Review->setRating($movie->vote_average);
$Review->setReviewDesc("");
Page::ShowUserReview($Review,$_SESSION["MOVIE"]);
$Reviews = null;
Page::showReviews($Reviews);

Page::footer();
?>