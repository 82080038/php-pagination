<?php 
require_once 'koneksi.php';
require_once 'helper.php';

$page = isset($_GET['halaman']) ? $_GET['halaman'] : 1;

// jumlah data/baris yang ingin ditampilkan perhalaman
$limit = 2;
// menentukan start data yang ditampilkan (offset)
$offset = ($page - 1) * $limit;
// query select data digunakan untuk menghitung total data
$sql  = "SELECT * FROM tbl_artikel order by tanggal desc"; 
$stmt = $dbh->prepare($sql);
$stmt->execute();

$total_data =  $stmt->rowCount();
$total_pages = ceil($total_data/$limit);
if(($page>$total_pages || $page < 1) && $total_data != 0){
	echo "<script>window.location = 'index.php';</script>";
}	

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.0/dist/css/bootstrap.min.css" integrity="sha384-B0vP5xmATw1+K9KRQjQERJvTumQW0nPEzvF6L/Z6nronJ3oUOFUFpCjEUQouq2+l" crossorigin="anonymous">
    <title>Daftar Lirik Lagu</title>
    <link rel="stylesheet" href="style.css">
    <script src="https://kit.fontawesome.com/a076d05399.js"></script>
</head>
<body>

	<div class="container pt-5">
		<div class="row">
		    <div class="col-8 offset-2 heading">
		        <h2 class="text-center">Daftar Lirik Lagu</h2>
		    </div>
		</div>
		<div id="posts" class="row pt-4">
			<?php 
							    
				$sql  .= " LIMIT $limit OFFSET $offset";
				$stmt = $dbh->prepare($sql);
				$stmt->execute();
				while($row = $stmt->fetchObject()){
			 ?>
	        <div class="item col-8 offset-2 mb-3">
	          <div class="card rounded shadow border-0">
	              <div class="card-body p-3">
	                <a href="#" class="text-dark"><h4><?=$row->judul?></h4></a>
	                <p class="text-muted small">Ditulis Oleh <strong><?=$row->penulis?></strong> | <?=date( 'M d, Y ', strtotime($row->tanggal) )?></p>
	                <p><?=limit_text($row->konten,35)?></p>
	                <a class="btn btn-dark float-right" href="#">Baca Selengkapnya</a>
	              </div>
	          </div>
	        </div>
       		<?php } ?>
    </div>
		
	</div>
	<div class="container-paging">
		<div class="pagination">
		    <ul> <!--pages or li are comes from javascript --> </ul>
		  </div>		
	</div>
  

   <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js" integrity="sha384-DfXdz2htPH0lsSSs5nCTpuj/zy4C+OGpamoFVy38MVBnE+IbbVYUew+OrCXaRkfj" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.0/dist/js/bootstrap.min.js" integrity="sha384-+YQ4JLhjyBLPDQt//I+STsc9iw4uQqACwlvpslubQzn4u2UU2UFM80nGisd026JF" crossorigin="anonymous"></script>

  <script>
 
		// selecting required element
		const element = document.querySelector(".pagination ul");
		let totalPages = <?=$total_pages?>;
		let page = <?=$page?>;
		//calling function with passing parameters and adding inside element which is ul tag
		if(totalPages < 6){
		  element.innerHTML = createPaginationUnderSix(totalPages, page);  
		}else{
		  element.innerHTML = createPagination(totalPages, page);  
		}

		function redirect_page(page) {
			const location = page==1 ? "index.php" : "index.php?halaman="+page;
			window.location = location;
		}			

		function createPaginationUnderSix(totalPages, page){
		  let liTag = '';
		  let active;
		  let startPage = 1;
		  let endPage = totalPages;

		  if(page > 1){ //show the next button if the page value is greater than 1
		    liTag += `<li class="btn prev" onclick="redirect_page(${page - 1})"><span><i class="fas fa-angle-left"></i> Sebelumnya</span></li>`;
		  }
		  
		  for (var plength = startPage; plength <= endPage; plength++) {
		    
		    if(page == plength){ //if page is equal to plength than assign active string in the active variable
		      active = "active";
		    }else{ //else leave empty to the active variable
		      active = "";
		    }
		    liTag += `<li class="numb ${active}" onclick="redirect_page(${plength})"><span>${plength}</span></li>`;
		  }

		  if (page < totalPages) { //show the next button if the page value is less than totalPage(20)
		    liTag += `<li class="btn next" onclick="redirect_page(${page + 1})"><span>Selanjutnya <i class="fas fa-angle-right"></i></span></li>`;
		  }



		  element.innerHTML = liTag; //add li tag inside ul tag
		  return liTag; //reurn the li tag
		}

		function createPagination(totalPages, page){
		  let liTag = '';
		  let active;
		  let beforePage = page - 1;
		  let afterPage = page + 1;
		  if(page > 1){ //show the next button if the page value is greater than 1
		    liTag += `<li class="btn prev" onclick="redirect_page(${page - 1})"><span><i class="fas fa-angle-left"></i> Sebelumnya</span></li>`;
		  }

		  if(page > 2){ //if page value is less than 2 then add 1 after the previous button
		    liTag += `<li class="first numb" onclick="redirect_page(1)"><span>1</span></li>`;
		    if(page > 3){ //if page value is greater than 3 then add this (...) after the first li or page
		      liTag += `<li class="dots"><span>...</span></li>`;
		    }
		  }

		  // how many pages or li show before the current li
		  if (page == totalPages) {
		    beforePage = beforePage - 2;
		  } else if (page == totalPages - 1) {
		    beforePage = beforePage - 1;
		  }
		  // how many pages or li show after the current li
		  if (page == 1) {
		    afterPage = afterPage + 2;
		  } else if (page == 2) {
		    afterPage  = afterPage + 1;
		  }

		  for (var plength = beforePage; plength <= afterPage; plength++) {
		    if (plength > totalPages) { //if plength is greater than totalPage length then continue
		      continue;
		    }
		    if (plength == 0) { //if plength is 0 than add +1 in plength value
		      plength = plength + 1;
		    }
		    if(page == plength){ //if page is equal to plength than assign active string in the active variable
		      active = "active";
		    }else{ //else leave empty to the active variable
		      active = "";
		    }
		    liTag += `<li class="numb ${active}" onclick="redirect_page( ${plength})"><span>${plength}</span></li>`;
		  }

		  if(page < totalPages - 1){ //if page value is less than totalPage value by -1 then show the last li or page
		    if(page < totalPages - 2){ //if page value is less than totalPage value by -2 then add this (...) before the last li or page
		      liTag += `<li class="dots"><span>...</span></li>`;
		    }
		    liTag += `<li class="last numb" onclick="redirect_page(${totalPages})"><span>${totalPages}</span></li>`;
		  }

		  if (page < totalPages) { //show the next button if the page value is less than totalPage(20)
		    liTag += `<li class="btn next" onclick="redirect_page(${page + 1})"><span>Selanjutnya <i class="fas fa-angle-right"></i></span></li>`;
		  }
		  element.innerHTML = liTag; //add li tag inside ul tag
		  return liTag; //reurn the li tag
		}


  </script>

</body>
</html>
