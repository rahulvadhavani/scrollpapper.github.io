 </div>   
    </div>
    </div>
    

    <!-- Logout Modal-->
    <div class="modal fade" id="logoutModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
        aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Ready to Leave?</h5>
                    <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span>
                    </button>
                </div>
                <div class="modal-body">Select "Logout" below if you are ready to end your current session.</div>
                <div class="modal-footer">
                    <button class="btn btn-secondary" type="button" data-dismiss="modal">Cancel</button>
                    <a class="btn btn-primary" href="{{URL::to('admin/logout')}}">Logout</a>
                </div>
            </div>
        </div>
    </div>

    <!-- Bootstrap core JavaScript-->
    <script src="{{url('vendor/bootstrap/js/bootstrap.bundle.min.js')}}"></script>
    <script src="{{url('assets/js/sweetalert.js')}}"></script>
    <script src="{{url('assets/js/toaster.min.js')}}"></script>
    <script src="{{url('assets/js/paginate.min.js')}}"></script>


    <!-- Core plugin JavaScript-->
    <script src="{{url('vendor/jquery-easing/jquery.easing.min.js')}}"></script>

    <!-- Custom scripts for all pages-->
    <script src="{{url('assets/js/admin_app.min.js')}}"></script>
<?php 

    if (isset($footer['js'])){
        for ($i = 0; $i < count($footer['js']); $i++) {
            if (strpos($footer['js'][$i], "https://") !== FALSE || strpos($footer['js'][$i], "http://") !== FALSE)
                echo '<script type="text/javascript" src="' . $footer['js'][$i] . '"></script>';
            else
                echo '<script type="text/javascript" src="' . \URL::to('assets/js/' . $footer['js'][$i]) . '"></script>';
        }
    }

?>

</body>
</html>
@yield('footer')