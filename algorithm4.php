<?php
// ＜アルゴリズムの注意点＞
// アルゴリズムではこれまでのように調べる力ではなく物事を論理的に考える力が必要です。
// 検索して答えを探して解いても考える力には繋がりません。
// まずは検索に頼らずに自分で処理手順を考えてみましょう。


// 以下はポーカーの役を判定するプログラムです。
// cards配列に格納したカードの役を判定し、結果表示してください。
// cards配列には計5枚、それぞれ絵柄(suit)、数字(numeber)を格納する
// 絵柄はheart, spade, diamond, clubのみ
// 数字は1-13のみ

// 上記以外の絵柄や数字が存在した場合、または同一の絵柄、数字がcards配列に存在した場合、
// 役の判定前に「手札が不正です」と表示してください。
// 役判定は関数に記述し、関数を呼び出して結果表示すること
// プログラムが完成したらcards配列を差し替えてすべての役で検証を行い、提出時にテストケースを示すこと

// <役>
// ワンペア・・・同じ数字２枚（ペア）が１組
// ツーペア・・・同じ数字２枚（ペア）が２組
// スリーペア・・・同じ数字３枚
// ストレート・・・数字の連番５枚（13と1は繋がらない）
// フラッシュ・・・同じマークが５枚
// フルハウス・・・同じ数字３枚が１組＋同じ数字２枚（ペア）が１組
// フォーカード・・・同じ数字４枚
// ストレートフラッシュ・・・数字の連番５枚＋同じマークが５枚
// ロイヤルストレートフラッシュ・・・1, 10, 11, 12, 13で同じマーク
// ※下の方が強い

// 表示例1）
// 手札は
// heart2 heart5 heart3 heart4 culb1
// 役はストレートです

// 表示例2）
// 手札は
// heart1 spade2 diamond11 club13 heart9
// 役はなしです

// 表示例3）
// 手札は
// heart1 heart1 heart3 heart4 heart5
// 手札は不正です


// 手札
$cards = [
    ['suit'=>'diamond', 'number'=>11],
    ['suit'=>'diamond', 'number'=>10],
    ['suit'=>'diamond', 'number'=>12],
    ['suit'=>'diamond', 'number'=>1],
    ['suit'=>'diamond', 'number'=>13],
];

function judge($cards) {

  //最終的な役を決める変数。
  $yaku = "なし";

  //カードの不正チェック
  for ( $i = 0 ; $i <= 4 ; $i++ ){
    //スート、数字不正判断
    if (
      ($cards[$i]["suit"] != "heart" &&
       $cards[$i]["suit"] != "spade" &&
       $cards[$i]["suit"] != "diamond" &&
       $cards[$i]["suit"] != "club" ) ||
      ($cards[$i]["number"] < 1 ||
       $cards[$i]["number"] > 13)
    )
    {
      $yaku = "不正";
    };
    //同じカードの有無についての不正判断
    for ( $j = $i+1 ; $j <= 4 ; $j++ ){
      $card1 = $cards[$i]["suit"].$cards[$i]["number"];
      $card2 = $cards[$j]["suit"].$cards[$j]["number"];
      if ( $card1 == $card2 ){
        $yaku = "不正";
      }
    }
  };


  //不正でないのならば、役を判定する。
  if ( $yaku == "不正" ){
    print "手札は不正";
  }
  else
  {
    //スートと番号それぞれの配列を作る。
    $suits = [];
    $numbers = [];
    for ( $i = 0 ; $i <= 4 ; $i++ ){
      $suit = $cards[$i]["suit"];
      $suits[] = $suit;
      //ex) $suits = ["heart","spade","diamond"～];
      $number = $cards[$i]["number"];
      $numbers[] = $number;
      //ex) $numbers = [1,10,13～];
    }

    //$numbersの順番を昇順にする。
    for ( $i = 0 ; $i <= 3 ; $i++ ){
      for ( $j = $i+1 ; $j <= 4 ; $j++ ){
        if ( $numbers[$i] > $numbers[$j] ){
          $n = $numbers[$i];
          $numbers[$i] = $numbers[$j];
          $numbers[$j] = $n;
        }
      }
    }

    //フラッシュの条件設定
    $flash = $suits[0] == $suits[1] &&
             $suits[0] == $suits[2] &&
             $suits[0] == $suits[3] &&
             $suits[0] == $suits[4] ;
    //フラッシュの役決定
    if ( $flash ){
      $yaku = "フラッシュ";
    }

    //ストレートの条件設定
    $straight = $numbers[1] == $numbers[0] + 1 &&
                $numbers[2] == $numbers[1] + 1 &&
                $numbers[3] == $numbers[2] + 1 &&
                $numbers[4] == $numbers[3] + 1 ;
    //ストレートの役決定
    if ( $straight ){
      $yaku = "ストレート";
    }

    // ストレートフラッシュの役決定
    if( $flash && $straight ){
      $yaku = "ストレートフラッシュ";
    }

    //ロイヤルストレートフラッシュの役決定
    $royal = [1,10,11,12,13];
    if ( $flash && $numbers == $royal ){
      $yaku = "ロイヤルストレートフラッシュ";
    }

    //$numbersの要素内の同じ値を数える配列の作成。
    //ex)$numbers=[1,1,3,4,5] ならば $same=[1]
    //ex)$numbers=[1,1,1,2,2] ならば $same=[1,1,1,2]　など、、、
    $same = [];
    for ( $i = 0 ; $i <= 3 ; $i++ ){
      for ( $j = $i+1 ; $j <= 4 ; $j++ ){
        if ( $numbers[$i] == $numbers[$j] ){
          $same[] = $numbers[$i];
        }
      }
    }
    //$sameの配列数に応じて役を決定する。
    switch (count($same)){
      case 1:
        $yaku = "ワンペア";
        break;
      case 2:
        $yaku = "ツーペア";
        break;
      case 3:
        $yaku = "スリーカード";
        break;
      case 4:
        $yaku = "フルハウス";
        break;
      case 6:
        $yaku = "フォーカード";
        break;
    }
    print "役は".$yaku;
  }
}
?>
<!DOCTYPE html>
<html lang="ja">
<head>
<meta charset="utf-8">
<title>ポーカー役判定</title>
</head>
<body>
    <section>
        <p>手札は</p>
        <p><?php foreach($cards as $card): ?><?=$card['suit'].$card['number'] ?><?php endforeach; ?></p>
        <p><?=judge($cards) ?>です。</p>
    </section>
</body>
</html>
