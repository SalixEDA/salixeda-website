<?php
// pages/history.php
$pageName = 'history';

// Используем шаблон
include 'pages/shorts.php';

?>

<style>
  .timeline {
    display: flex;
    justify-content: space-between;
    align-items: flex-start;
    margin: 40px 0 30px 0;
    position: relative;
  }
  .timeline::before {
    content: '';
    position: absolute;
    top: 24px;
    left: 0;
    right: 60px;
    height: 3px;
    background: #333;
    z-index: 0;
  }
  .timeline-node {
    position: relative;
    z-index: 1;
    text-align: center;
    flex: 1;
  }
  .timeline-year {
    background: #fff;
    display: inline-block;
    padding: 0 8px;
    font-weight: bold;
    font-size: 1.1em;
    position: relative;
    top: -8px;
  }
  .timeline-year::before {
    content: '';
    position: absolute;
    left: 50%;
    transform: translateX(-50%);
    top: 28px;
    width: 10px;
    height: 10px;
    background: #333;
    border-radius: 50%;
  }
  .timeline-icon {
    margin-top: 20px;
    font-size: 1.8em;
  }
  .timeline-label {
    margin-top: 8px;
    font-size: 0.85em;
    font-weight: 500;
    color: #222;
  }
  .timeline-continue {
    position: relative;
    z-index: 1;
    text-align: center;
    flex: 0 0 50px;
    margin-left: 10px;
  }
  .timeline-continue span {
    display: inline-block;
    font-size: 1.6em;
    color: #555;
    margin-top: 28px;
  }
  .timeline-continue .timeline-label {
    margin-top: 5px;
    font-size: 0.75em;
    color: #666;
  }
</style>