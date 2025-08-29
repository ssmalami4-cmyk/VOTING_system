<?php include('includes/header.php'); ?>

<div class="container mt-5">
    <div id="carouselExampleIndicators" class="carousel slide" data-ride="carousel">
        <ol class="carousel-indicators">
            <li data-target="#carouselExampleIndicators" data-slide-to="0" class="active"></li>
            <li data-target="#carouselExampleIndicators" data-slide-to="1"></li>
            <li data-target="#carouselExampleIndicators" data-slide-to="2"></li>
        </ol>
        <div class="carousel-inner">
            <div class="carousel-item active">
                <img class="d-block w-100" src="./assets/images/1.jpeg" alt="First slide">
                <div class="carousel-caption d-none d-md-block text-center">
                    <div class="carousel-text-background">
                        <h1>Welcome to the Voting System</h1>
                        <p>Participate in the election by voting for your preferred candidates.</p>
                    </div>
                </div>
            </div>
            <div class="carousel-item">
                <img class="d-block w-100" src="./assets/images/5.jpeg" alt="Second slide">
                <div class="carousel-caption d-none d-md-block text-center">
                    <div class="carousel-text-background">
                        <h1>Admin, Candidate, Voter Access</h1>
                        <p>Login to manage, register, or participate in the voting process.</p>
                    </div>
                </div>
            </div>
            <div class="carousel-item">
                <img class="d-block w-100" src="./assets/images/4.jpeg" alt="Third slide">
                <div class="carousel-caption d-none d-md-block text-center">
                    <div class="carousel-text-background">
                        <h1>Secure and Fair Voting</h1>
                        <p>Ensuring a transparent and reliable election process.</p>
                    </div>
                </div>
            </div>
        </div>
        <a class="carousel-control-prev" href="#carouselExampleIndicators" role="button" data-slide="prev">
            <span class="carousel-control-prev-icon" aria-hidden="true"></span>
            <span class="sr-only">Previous</span>
        </a>
        <a class="carousel-control-next" href="#carouselExampleIndicators" role="button" data-slide="next">
            <span class="carousel-control-next-icon" aria-hidden="true"></span>
            <span class="sr-only">Next</span>
        </a>
    </div>

    <div class="text-center mt-5">
        <p class="lead">Join the election process by choosing your role:</p>
        <div class="btn-group btn-group-lg" role="group" aria-label="User Roles">
            <a class="btn btn-primary" href="./admin/admin_login.php" role="button">Admin Login</a>
            <a class="btn btn-success" href="./candidate/candidate_register.php" role="button">Candidate Registration</a>
            <a class="btn btn-info" href="./voter/voter_login.php" role="button">Voter Login</a>
        </div>
    </div>

    <div class="row mt-5">
        <div class="col-md-4">
            <div class="card">
                <img class="card-img-top" src="./assets/images/10.jpg" alt="Card image cap">
                <div class="card-body">
                    <h5 class="card-title">About Our System</h5>
                    <p class="card-text">Learn about the features and benefits of our secure voting system.</p>
                    <button class="btn btn-outline-primary" data-toggle="modal" data-target="#aboutModal">Learn More</button>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card">
                <img class="card-img-top" src="./assets/images/14.jpg" alt="Card image cap">
                <div class="card-body">
                    <h5 class="card-title">How to Participate</h5>
                    <p class="card-text">Find out how you can participate as a voter, candidate, or admin.</p>
                    <button class="btn btn-outline-success" data-toggle="modal" data-target="#participateModal">Get Started</button>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card">
                <img class="card-img-top" src="./assets/images/9.jpg" alt="Card image cap">
                <div class="card-body">
                    <h5 class="card-title">FAQs</h5>
                    <p class="card-text">Common questions and answers about the voting process.</p>
                    <button class="btn btn-outline-info" data-toggle="modal" data-target="#faqsModal">Read FAQs</button>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modals -->
<!-- About Our System Modal -->
<div class="modal fade" id="aboutModal" tabindex="-1" role="dialog" aria-labelledby="aboutModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="aboutModalLabel">About Our System</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <p>Our voting system provides a secure and reliable platform for conducting elections. It ensures transparency and fairness, making the voting process straightforward for all participants. Learn more about our system's features and benefits.</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>

<!-- How to Participate Modal -->
<div class="modal fade" id="participateModal" tabindex="-1" role="dialog" aria-labelledby="participateModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="participateModalLabel">How to Participate</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <p>Participating in the election is easy! As a voter, you can select your preferred candidates. As a candidate, you can register and run for office. Admins can manage the elections, candidates, and voters. Follow the instructions provided in your respective role to get started.</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>

<!-- FAQs Modal -->
<div class="modal fade" id="faqsModal" tabindex="-1" role="dialog" aria-labelledby="faqsModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="faqsModalLabel">FAQs</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <p>Here are some frequently asked questions about the voting process:</p>
                <ul>
                    <li><strong>How can I vote?</strong> Log in as a voter and select your preferred candidates.</li>
                    <li><strong>How do I register as a candidate?</strong> Visit the candidate registration page and provide the required details.</li>
                    <li><strong>Who can be an admin?</strong> Admins are responsible for managing the elections and users. Admins have special access privileges.</li>
                </ul>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>

<?php include('includes/footer.php'); ?>

<style>
    .carousel-item img {
        height: 500px;
        object-fit: cover;
    }

    .carousel-caption {
        position: absolute;
        top: 50%;
        left: 50%;
        transform: translate(-50%, -50%);
        text-align: center;
    }

    .carousel-caption h1, .carousel-caption p {
        background-color: rgba(0, 0, 0, 0.5); 
        padding: 10px;
        border-radius: 10px;
    }

    .carousel-caption h1 {
        font-size: 2.5rem;
        font-weight: bold;
        color: #ffffff;
    }

    .carousel-caption p {
        font-size: 1.25rem;
        color: #ffffff;
    }

    .card-img-top {
        height: 200px;
        object-fit: cover;
    }

    .card-title {
        font-size: 1.5rem;
    }

    .card-text {
        font-size: 1rem;
    }
</style>
