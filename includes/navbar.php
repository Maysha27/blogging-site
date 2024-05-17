
<nav class="navbar sticky-top navbar-expand-lg navbar-dark bg-dark">
        <div class="container">
        <div class="logo">
        <a href="xyz.php"><img src="admin/assets/img/logo.png" alt="CraftHub"></a>
                <span class="d-none d-lg-block">CraftHub</span>
                </div>
          <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarScroll" aria-controls="navbarScroll" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
          </button>
          <div class="collapse navbar-collapse" id="navbarScroll">
            <ul class="navbar-nav me-auto my-2 my-lg-0 navbar-nav-scroll" style="--bs-scroll-height: 100px;">
            <?php
            $navQuery= "SELECT * FROM menu";
            $runNav= mysqli_query($conn,$navQuery);
            while($menu=mysqli_fetch_assoc($runNav)){
              $no = getSubMenuNo($conn,$menu['id']);
              if (!$no){
                ?>
  <li class="nav-item">
                <a class="nav-link " aria-current="page" href="<?=$menu['action']?>"><?=$menu['name']?></a>
              </li>
                <?php
              }else{
                ?>
                <li class="nav-item dropdown">
                <a class="nav-link dropdown-toggle" href="<?=$menu['action']?>" id="navbarScrollingDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false"><?=$menu['name']?>
                </a>
                <ul class="dropdown-menu" aria-labelledby="navbarScrollingDropdown">
                 <?php
                 $submenus= getSubMenu($conn, $menu['id']);
                 foreach($submenus as $sm){
                  ?>
                    <li><a class="dropdown-item" href="#<?=$sm['action']?>"><?=$sm['name']?></a></li>
                  <?php
                 }
                 ?>
                
                  
                </ul>
              </li>
                <?php
              }
              ?>

              <?php
            }
            ?>
               <!-- <li class="nav-item">
                <a class="nav-link" href="#">Link</a>
              </li> -->
              
              <li class="nav-item">
                <a class="nav-link disabled" href="#" tabindex="-1" aria-disabled="true">Link</a>
              </li>
            </ul>
            <form class="d-flex">
              <input class="form-control me-2" name="search" type="search" placeholder="Search" aria-label="Search">
              <button class="btn btn-outline-success" type="submit">Search</button>
            </form>
          </div>
        </div>
      </nav>   