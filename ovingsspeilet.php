<?php

require('config.php');

date_default_timezone_set('Europe/Oslo');

function db() {
  static $dbh;
  if (!$dbh) {
    try {
      $dbh = new PDO(DB_DSN, DB_USER, DB_PASS);
    } catch (PDOException $err) {
      echo 'DB connection failed: ' . $err->getMessage();
      exit();
    }
  }
  return $dbh;
}

switch ($_SERVER['REQUEST_METHOD'] . ' ' . $_GET['action']) {
default:
  header('Content-Type: text/html; charset="utf-8"');
  readfile('ovingsspeilet.html');
  break;

case 'GET events':
  header('Content-Type: application/json; charset="utf-8"');

  $values =
    [ date('Y-m-d H:i:s', strtotime($_GET['start']))
    , date('Y-m-d H:i:s', strtotime($_GET['end']))
    ];
  $sql = 'SELECT * FROM `ovingsspeilet` WHERE `start` >= ? AND `end` <= ?';
  $sth = db()->prepare($sql);
  $sth->execute($values);

  $first = true;
  while ($row = $sth->fetchObject()) {
    $json =
      [ 'id' => $row->id
      , 'start' => $row->start
      , 'end' => $row->end
      , 'title' => $row->title
      , 'details' => $row->details
      , 'contact_name' => $row->contact_name
      , 'contact_phone' => $row->contact_phone
      ];
    if ($first) {
      $first = false;
      echo "[ ";
    } else {
      echo "\n, ";
    }
    echo json_encode($json);
  }
  echo "\n]";
  break;

case 'POST delete':
  header('Content-Type: text/plain; charset="utf-8"');
  $sql = 'DELETE FROM `ovingsspeilet` WHERE `id` = ?';
  $sth = db()->prepare($sql);
  echo $sth->execute([ (int) $_POST['id'] ]);
  break;

case 'POST save':
  header('Content-Type: text/plain; charset="utf-8"');

  $values =
    [ DateTime::createFromFormat('Y-m-d\TH:i+', $_POST['start'])->format('Y-m-d H:i:s')
    , DateTime::createFromFormat('Y-m-d\TH:i+', $_POST['end'])->format('Y-m-d H:i:s')
    , filter_input(INPUT_POST, 'contact_name', FILTER_UNSAFE_RAW)
    , filter_input(INPUT_POST, 'contact_phone', FILTER_UNSAFE_RAW)
    , filter_input(INPUT_POST, 'title', FILTER_UNSAFE_RAW)
    , filter_input(INPUT_POST, 'details', FILTER_UNSAFE_RAW)
    ];

  $id = (int) $_POST['id'];

  $sql = '';
  if ($id) {
    $sql .= 'UPDATE';
  } else {
    $sql .= 'INSERT INTO';
  }
  $sql .= '
     `ovingsspeilet`
    SET
      `start` = ?,
      `end` = ?,
      `contact_name` = ?,
      `contact_phone` = ?,
      `title` = ?,
      `details` = ?
      ';
  if ($id) {
    $sql .= 'WHERE `id` = ?';
    $values[] = $id;
  }

  $sth = db()->prepare($sql);
  if ($sth->execute($values)) {
    if (!$id) {
      $id = db()->lastInsertId();
    }
    echo $id;
  }
  break;
}
