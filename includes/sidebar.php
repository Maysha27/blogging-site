<div class="col-4">
        <!-- <div class="card mb-3">
            <h5 class="card-header">Featured</h5>
            <div class="card-body">
              <h5 class="card-title">Special title treatment</h5>
              <p class="card-text">With supporting text below as a natural lead-in to additional content.</p>
              <a href="#" class="btn btn-primary">Go somewhere</a>
            </div>
        </div>


        <div class="card mb-3">
            <h5 class="card-header">Featured</h5>
            <div class="card-body">
              <h5 class="card-title">Special title treatment</h5>
              <p class="card-text">With supporting text below as a natural lead-in to additional content.</p>
              <a href="#" class="btn btn-primary">Go somewhere</a>
            </div>
        </div> -->

        <?php 
        if(isset($_GET['id'])){
          ?>
            <div class="card mb-3">
            <h5 class="card-header">Comments</h5>
            <?php
            $comments=getComments($conn, $post_id);
            if(count($comments)<1){
              echo '<div class= "card-body"><p class="card-text">No Comments..</p></div>';
            }
            foreach($comments as $comment){
              ?>
                <div class="card-body">
              <h5 class="card-title"><?=$comment['name']?></h5>
              <span class="text-secondary"><small><?=date('F, js, Y;', strtotime($post['created_at']))?></small></span>
              <p class="card-text"><?=$comment['comment']?></p>
              
            </div>
              <?php
            }
            ?>
            
        </div>
          <?php
        }
        ?>

        
          
    </div>