<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    <title>E-commerce</title>
    <!-- Font Awesome -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet" />
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css?family=Roboto:300,400,500,700&display=swap" rel="stylesheet" />
    <!-- MDB -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/mdb-ui-kit/7.2.0/mdb.min.css" rel="stylesheet" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <link rel="stylesheet" href="<?= base_url() ?>assets/css/owl.carousel.min.css">
    <link rel="stylesheet" href="<?= base_url() ?>assets/css/owl.theme.default.min.css">
    <link rel="stylesheet" href="<?= base_url() ?>assets/css/owl.theme.green.min.css">
    <style>
        .navbar-dark .nav-item .nav-link {
            color: #fff;
        }

        .navbar-dark .nav-item .nav-link:hover {
            background-color: rgba(255, 255, 255, 0.1);
            transition: all 0.3s ease;
            border-radius: 0.25rem;
            color: #fff;
        }

        .fa-li {
            position: relative;
            left: 0;
        }

        /* .card-container{
            margin: 10px 20px 50px;
        } */

        .card {
            transition-duration: 0.5s;
            cursor: pointer;
            position: relative;
            top: 40%;
            font-size: 10px;
        }

        .card:hover {
            transform: scale(1.05);
            transition: ease-in-out;
        }

        .items {
            margin: unset;
        }

        .fa-shopping-cart:hover {
            color: blue;
            transform: scale(1.03);
        }

        footer {
            margin-top: 250px;
        }
    </style>
</head>

<body>
    <section>
        <!-- Navbar -->
        <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
            <!-- Container wrapper -->
            <div class="container">
                <!-- Navbar brand -->
                <a class="navbar-brand" href="#">
                    <img src="<?= base_url() ?>assets/images/MobileLegendsLogoFinal.png" id="shooping" alt="Shopping" draggable="false" height="30" /></a>
                <!-- Toggle button -->
                <button class="navbar-toggler" type="button" data-mdb-toggle="collapse" data-mdb-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
                    <i class="fas fa-bars"></i>
                </button>

                <!-- Collapsible wrapper -->
                <div class="collapse navbar-collapse" id="navbarSupportedContent">
                    <!-- Left links -->
                    <!-- <ul class="navbar-nav me-3">
                        <li class="nav-item">
                            <a class="nav-link active d-flex align-items-center" aria-current="page" href="#"><i class="fas fa-bars pe-2"></i>Menu</a>
                            <a class="nav-link active d-flex align-items-center" aria-current="page" href="#"><i class="fas fa-bars pe-2"></i>Dota 2</a>
                            <a class="nav-link active d-flex align-items-center" aria-current="page" href="#"><i class="fas fa-bars pe-2"></i>League of Legends</a>
                            <a class="nav-link active d-flex align-items-center" aria-current="page" href="#"><i class="fas fa-bars pe-2"></i>Call of Duty</a>
                            <a class="nav-link active d-flex align-items-center" aria-current="page" href="#"><i class="fas fa-bars pe-2"></i>Wild Rift</a>
                            <a class="nav-link active d-flex align-items-center" aria-current="page" href="#"><i class="fas fa-bars pe-2"></i>Valorant</a>
                        </li>
                    </ul> -->

                    <div class="dropdown bg-transparent p-2 bd-highlight">
                        <button class="btn text-white d-flex fs-6" type="button" id="dropdownMenuButton" data-mdb-dropdown-init data-mdb-ripple-init aria-expanded="false">
                            <i class="fas fa-bars pe-2 fs-5 pt-0"></i>Menu
                        </button>
                        <ul class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                            <li><a class="dropdown-item" href="#" style="font-family: Trajan Pro Bold;">
                                    <img src="<?= base_url() ?>assets/images/DOTA2.png" id="shooping" alt="Shopping" draggable="false" height="30" /> DOTA 2
                                </a></li>

                            <li><a class="dropdown-item" href="#" style="font-family: HITMARKER; position:static; right: 13px; ">
                                    <img src="<?= base_url() ?>assets/images/CODM.png " id="shooping" alt="Shopping" draggable="false" height="30" /> Call of Duty Mobile
                                </a></li>

                            <li><a class="dropdown-item" href="#" style="font-family: Moderna; position:static; right: 8px;">
                                    <img src="<?= base_url() ?>assets/images/LOL.png" id="shooping" alt="Shopping" draggable="false" height="30"> League of Legends
                                </a></li>

                            <li><a class="dropdown-item" href="#" style="font-family: Story Brush Slanted;">
                                    <img src="<?= base_url() ?>assets/images/WILDR.png" id="shooping" alt="Shopping" draggable="false" height="30" /> Wild Rift
                                </a></li>

                            <li><a class="dropdown-item" href="#" style="font-family: Tungsten;">
                                    <img src="<?= base_url() ?>assets/images/VALORANT.png" id="shooping" alt="Shopping" draggable="false" height="30" /> VALORANT
                                </a></li>
                        </ul>
                    </div>


                    <!-- Left links -->


                    <form class="d-flex align-items-center w-100 form-search">
                        <div class="input-group">
                            <!-- <button class="btn btn-light dropdown-toggle shadow-0" type="button" data-mdb-toggle="dropdown" aria-expanded="false" style="padding-bottom: 0.4rem;">
                                All
                            </button> -->

                            <button class="btn btn-light dropdown-toggle shadow-0" type="button" id="dropdownMenuButton" data-mdb-dropdown-init data-mdb-ripple-init aria-expanded="false">
                                Categories
                            </button>

                            <ul class="dropdown-menu dropdown-menu-dark fa-ul">

                                <li>
                                    <a class="dropdown-item" href="#"><span class="fa-li pe-2"><i class="fas fa-search"></i></span>All</a>
                                </li>
                                <li>
                                    <a class="dropdown-item" href="<?= base_url() ?>home/3"><span class="fa-li pe-2"><i class="fa fa-arrows"></i></span>Physical Items</a>
                                </li>
                                <li>
                                    <a class="dropdown-item" href="<?= base_url() ?>home/4"><span class="fa-li pe-2"><i class="fa fa-magic"></i></span>Mage Items
                                    </a>
                                </li>
                                <li>
                                    <a class="dropdown-item" href="<?= base_url() ?>home/5"><span class="fa-li pe-2"><i class="fa fa-shield"></i></i></span>Tank Items</a>
                                </li>
                                <li>
                                    <a class="dropdown-item" href="<?= base_url() ?>home/6"><span class="fa-li pe-2"><i class="fa fa-plus"></i></span>Jungle</a>
                                </li>
                                <li>
                                    <a class="dropdown-item" href="<?= base_url() ?>home/7"><span class="fa-li pe-2"><i class='fas fa-shoe-prints'></i></span>Roam Items</a>
                                </li>
                                <li>
                                    <hr class="dropdown-divider" />
                                </li>
                                <li>
                                    <a class="dropdown-item" href="#"><span class="fa-li pe-2"><i class="fas fa-search-plus"></i></span>Advanced
                                        search<i class="fas fa-chevron-right ps-2"></i></a>
                                </li>
                            </ul>
                            <input type="search" class="form-control" placeholder="Search" aria-label="Search" />
                        </div>
                        <a href="#!" class="text-white"><i class="fas fa-search ps-3"></i></a>
                    </form>

                    <!---------------CART--------------->
                    <ul class="navbar-nav ms-3">
                        <li class="nav-item me-3">
                            <a class="nav-link d-flex align-items-center" href="#!">Cart</a>
                        </li>

                        <!---------------WATCHLIST--------------->
                        <li class="nav-item">
                            <a class="nav-link d-flex align-items-center me-3" href="#!">
                                <i class="fas fa-bookmark pe-2"></i> Watchlist
                            </a>
                        </li>

                        <!---------------DIAMONDS--------------->
                        <?php if (isset($_SESSION['username'])) : ?>
                            <li class="nav-item">
                                <a class="nav-link d-flex align-items-center me-3" href="#!">
                                    <i class="fa fa-diamond pe-2"></i> Diamonds
                                </a>
                            </li>
                        <?php endif; ?>

                        <!---------------LOGOUT--------------->
                        <li class="nav-item" style="width: 65px;">
                            <?php if (isset($_SESSION['username'])) : ?>
                                <div class="dropdown">
                                    <a class="dropdown-toggle nav-link d-flex align-items-center" style="width: 155%;" type="button" id="dropdownMenuButton1" data-bs-toggle="dropdown" aria-expanded="false">
                                        <?= $_SESSION['username'] ?>
                                    </a>
                                    <ul class="dropdown-menu" aria-labelledby="dropdownMenuButton1">
                                        <li><a class="dropdown-item" href="#">Action</a></li>
                                        <li><a class="dropdown-item" href="#">Another action</a></li>
                                        <li><a class="dropdown-item" href="#">Account Settings</a></li>
                                        <li><a class="dropdown-item" id="LogoutBtn" href="#">Logout</a></li>
                                    </ul>
                                </div>
                                <!-- <a class="nav-link d-flex align-items-center" id="" href="#!"><?= $_SESSION['username'] ?> </a> -->
                            <?php else : ?>
                                <a class="nav-link d-flex align-items-center LoginBtn" id="" href="#!">Sign In</a>
                            <?php endif; ?>
                        </li>
                    </ul>
                </div>
                <!-- Collapsible wrapper -->
            </div>
            <!-- Container wrapper -->
        </nav>
        <!-- Navbar -->


        <div class="row m-5 ">
            <div class="container d-flex justify-content-center">
                <div class="owl-carousel owl-theme">

                    <?php foreach ($content as $key => $data) : ?>
                        <div class="item p-2 text-center">
                            <div class="card">
                                <center>
                                    <img style="width: 80px;" class="description" data-image="<?= base_url() ?>assets/images/<?= $data['ItemImages'] ?>" src="<?= base_url() ?>assets/images/<?= $data['ItemImages'] ?>" alt="">
                                </center>

                                <p></p><?= $data['ItemName'] ?></p>
                            </div>

                        </div>
                    <?php endforeach; ?>
                </div>
            </div>
        </div>

        <div class="container">
            <div id="content">
            </div>
        </div>
        </div>
        <!-- Container wrapper -->
        </nav>
        <!-- Navbar -->
        <!-- Modal -->

        <!-- Modal -->
        <div class="modal fade" id="LoginModal" tabindex="-1" aria-labelledby="LoginModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="LoginModalLabel">Welcome to MLBB</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <form class="m-3" id="LoginForm">
                        <div class="modal-body">
                            <div class="input-group mb-3">
                                <div class="form-outline" data-mdb-input-init>
                                    <input type="text" id="UserName" class="form-control" />
                                    <label class="form-label" for="typeText">Username</label>
                                </div>
                            </div>
                            <div class="input-group mb-3">
                                <div class="form-outline" data-mdb-input-init>
                                    <input type="password" id="Password" class="form-control" />
                                    <label class="form-label" for="typeText">Password</label>
                                </div>
                            </div>
                            <a href="#" class="forgot-pass">Forgot Password?</a>
                            <div class="m-8">
                                <button type="button" class="btn btn-success pull-left mt-3 mb-3">Create New Account</button>
                                <button type="submit" class="btn btn-primary pull-right mt-3 mb-3"> Login</button>
                            </div>

                            <div class="container">
                                <div id="content">
                                </div>
                            </div>




                            <!-------Modal--------->
                            <!-- <div class="modal fade" id="LoginModal" tabindex="-1" aria-labelledby="LoginModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="LoginModalLabel">Welcome to MLBB</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <form>
                        <div class="modal-body">
                            <div class="input-group mb-3">
                                <div class="form-outline" data-mdb-input-init>
                                    <input type="text" id="typeText" class="form-control" />
                                    <label class="form-label" for="typeText">Username</label>
                                </div>
                            </div>
                            <div class="input-group mb-3">
                                <div class="form-outline" data-mdb-input-init>
                                    <input type="password" id="typeText" class="form-control" />
                                    <label class="form-label" for="typeText">Password</label>
                                </div>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                                <button type="button" class="btn btn-primary">Save changes</button>
                            </div>
                    </form>
                </div>
            </div>
        </div>  -->



    </section>

    <div class="modal fade" id="DescModal" tabindex="-1" aria-labelledby="DescModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="DescModalLabel"> </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>

                <div class="modal-body">
                    <div class="m-3">
                        <span id="image-desc"></span>
                        <span id="description"></span>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        <button type="button" class="btn btn-primary">Save changes</button>
                    </div>

                </div>
            </div>
        </div>



        <!-- MDB -->
        <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/mdb-ui-kit/7.2.0/mdb.umd.min.js"></script>
        <!-- MDB -->
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>
        <script src="<?= base_url() ?>assets/js/jquery.min.js"></script>
        <script src="<?= base_url() ?>assets/js/ajaxgoogleapis.min.js"></script>
        <!-- <script src="<?= base_url() ?>assets/js/ajaxgoogleapis.min.js"></script> -->
        <script src="<?= base_url() ?>assets/js/owl.carousel.min.js"></script>
        <script>
            var base_url = '<?= base_url() ?>';

            var sess = '<?= empty($_SESSION['username']) ? 0 : 1 ?>';

            $(document).ready(function() {

                $('.owl-carousel').owlCarousel({
                    loop: true,
                    margin: 2,
                    // nav: true,
                    responsive: {
                        0: {
                            items: 1
                        },
                        600: {
                            items: 3
                        },
                        1000: {
                            items: 8
                        }
                    }
                })
                $(document).on('click', ".LoginBtn", function() {
                    console.log('here')
                    if (sess == 1) {
                        alert('Item Added');

                    } else {
                        $("#LoginModal").modal('toggle');
                    }

                })


                $(document).on('submit', '#LoginForm', function(e) {
                    e.preventDefault();
                    $.ajax({
                        type: "POST", //method
                        url: base_url + 'login-user', //action
                        data: {
                            id: 1,
                            UserName: $('#UserName').val(),
                            Password: $('#Password').val(),
                        }, // inputs
                        datatype: 'json', // return data type
                        success: function(result) {


                            if (result == 1) {
                                location.reload();
                            }
                            if (result == 0) {
                                alert("User Not Found");
                            }
                            if (result == 2) {
                                alert("Password Not Match");
                            }
                        }

                    })



                })

                $(document).on('click', '#LogoutBtn', function() {

                    location.href = base_url + 'Logout';
                })
            })

            $(window).on('load', function() {

                $.ajax({
                    type: "POST", //method
                    url: base_url + 'get-item', //action
                    data: {
                        id: 1
                    }, // inputs
                    datatype: 'json', // return data type
                    success: function(result) {

                        // console.log(JSON.parse(result)[0].ItemName)

                        //create data here
                        var item = `<div class="row">`;
                        $.each(JSON.parse(result), function(k, v) {
                            item += `<div class="col-md-2 col-sm-4 col-lg-2"> 
                                    <div class="card m-1 p-3 mx-auto ml-15 "> 
                                    <div class="row">
                                    <i class="fa-solid fa-ellipsis pull-right" style="position: absolute; width: 10px; right: 19px; top: 10px; font-size: 20px;"></i>
                                    </div>
                                        <img  class="description" src="${base_url}assets/images/${v.ItemImages}" data-desc="${v.Description}" data-name="${v.ItemName} " data-image="${base_url}assets/images/${v.ItemImages}" alt="">
                                        <div>
                                       
                                        <div style="height:50px;">
                                            <p class="items text-center pt-4 fw-bold text-uppercase">${v.ItemName} </p>
                                        </div>
                                        <div style="height:50px; margin-bottom:5px;">
                                            <p class="items text-center fw-bold text-uppercase ">Item price : ${v.ItemPrice}</p>
                                        </div>
                                        
                                        
                                        
                                        <i class="fa fa-shopping-cart pull-right LoginBtn" style="font-size:24px"></i>
                                        </div>
                                    </div>
                                </div>`;
                        })
                        // console.log(item)
                        item += `</div>`;

                        $('#content').html(item);
                        $(document).on('click', '.description', function() {
                            var image = $(this).data('image')
                            var name = $(this).data('name');
                            var desc = $(this).data('desc')
                            $('#DescModalLabel').text(name)
                            $('#DescModal').modal('toggle');
                            $('#description').html(desc);

                            $('#image-desc').html(`<img  style="width:100%" class="description" src="${image}" >`)
                        })
                    }
                });
            })
        </script>
</body>

</html>