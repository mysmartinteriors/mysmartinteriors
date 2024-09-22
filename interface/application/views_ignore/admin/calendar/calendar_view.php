<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width initial-scale=1.0">
    <title><?php echo $title ?></title>
    <link rel="shortcut icon" type="image/png" href="<?php echo base_url() ?>ui/images/favicon.png">
    <!-- PAGE LEVEL STYLES-->
    <link href="<?php echo base_url() ?>ui/assets/plugins/fullcalendar/dist/fullcalendar.min.css" rel="stylesheet" />
    <link href="<?php echo base_url() ?>ui/assets/plugins/fullcalendar/dist/fullcalendar.print.min.css" rel="stylesheet" media="print" />
    <link href="<?php echo base_url() ?>ui/assets/plugins/smalot-bootstrap-datetimepicker/css/bootstrap-datetimepicker.min.css" rel="stylesheet" />
    <?php echo $commonCss ?>
</head>

<body class="fixed-navbar fixed-layout">
    <div class="page-wrapper">
        <!-- START HEADER-->
        <?php echo $header_main ?>
        <!-- END HEADER-->
        <!-- START SIDEBAR-->
        <?php echo $header_menu ?>
        <!-- END SIDEBAR-->
        <div class="content-wrapper">
            <div class="page-heading">
                <h1 class="page-title">Calendar Events</h1>
                <ol class="breadcrumb">
                    <li class="breadcrumb-item">
                        <a href="<?php echo base_url() ?>admin/dashboard"><i class="la la-home font-20"></i></a>
                    <li class="breadcrumb-item">Calendar</li>
                </ol>
            </div>
            <!-- START PAGE CONTENT-->
            <div class="page-content">
                <div class="row">
                    <div class="col-md-3">
                        <div class="ibox">
                            <div class="ibox-head">
                                <div class="ibox-title">DRAGGABLE EVENTS</div>
                            </div>
                            <div class="ibox-body p-3">
                                <div id="external-events">
                                    <div class="ex-event" data-class="fc-event-primary"><i class="badge-point badge-primary mr-3"></i>Business</div>
                                    <div class="ex-event" data-class="fc-event-warning"><i class="badge-point badge-warning mr-3"></i>Reporting</div>
                                    <div class="ex-event" data-class="fc-event-success"><i class="badge-point badge-success mr-3"></i>Meeting</div>
                                    <div class="ex-event" data-class="fc-event-danger"><i class="badge-point badge-danger mr-3"></i>Important</div>
                                    <div class="ex-event" data-class="fc-event-info"><i class="badge-point badge-info mr-3"></i>Personal</div>
                                    <p class="ml-2 mt-4">
                                        <label class="checkbox checkbox-primary">
                                            <input id="drop-remove" type="checkbox">
                                            <span class="input-span"></span>remove after drop</label>
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-9">
                        <div class="ibox">
                            <div class="ibox-head">
                                <div class="ibox-title">CALENDAR</div>
                                <button class="btn btn-primary btn-rounded btn-air my-3" data-toggle="modal" data-target="#new-event-modal">
                                    <span class="btn-icon"><i class="la la-plus"></i>New Event</span>
                                </button>
                            </div>
                            <div class="ibox-body">
                                <div id="calendar"></div>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- New Event Dialog-->
                <div class="modal fade" id="new-event-modal" tabindex="-1" role="dialog">
                    <div class="modal-dialog" role="document">
                        <form class="modal-content form-horizontal" id="newEventForm" action="javascript:;">
                            <div class="modal-header p-4">
                                <h5 class="modal-title">NEW EVENT</h5>
                                <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">×</span>
                                </button>
                            </div>
                            <div class="modal-body p-4">
                                <div class="form-group mb-4">
                                    <label class="text-muted mb-3">Category</label>
                                    <div>
                                        <label class="radio radio-outline-primary radio-inline check-single" data-toggle="tooltip" data-original-title="General">
                                            <input type="radio" name="category" checked value="fc-event-primary">
                                            <span class="input-span"></span>
                                        </label>
                                        <label class="radio radio-outline-warning radio-inline check-single" data-toggle="tooltip" data-original-title="Payment">
                                            <input type="radio" name="category" value="fc-event-warning">
                                            <span class="input-span"></span>
                                        </label>
                                        <label class="radio radio-outline-success radio-inline check-single" data-toggle="tooltip" data-original-title="Technical">
                                            <input type="radio" name="category" value="fc-event-success">
                                            <span class="input-span"></span>
                                        </label>
                                        <label class="radio radio-outline-danger radio-inline check-single" data-toggle="tooltip" data-original-title="Registration">
                                            <input type="radio" name="category" value="fc-event-danger">
                                            <span class="input-span"></span>
                                        </label>
                                        <label class="radio radio-outline-info radio-inline check-single" data-toggle="tooltip" data-original-title="Security">
                                            <input type="radio" name="category" value="fc-event-info">
                                            <span class="input-span"></span>
                                        </label>
                                    </div>
                                </div>
                                <div class="form-group mb-4">
                                    <input class="form-control form-control-line" id="new-event-title" type="text" name="title" placeholder="Title">
                                </div>
                                <div class="row">
                                    <div class="col-6 form-group mb-4">
                                        <label class="col-form-label text-muted">Start:</label>
                                        <div class="input-group-icon input-group-icon-right">
                                            <span class="input-icon input-icon-right"><i class="fa fa-calendar-check-o"></i></span>
                                            <input class="form-control form-control-line datepicker date" id="new-event-start" type="text" name="start" value="">
                                        </div>
                                    </div>
                                    <div class="col-6 form-group mb-4">
                                        <label class="col-form-label text-muted">End:</label>
                                        <div class="input-group-icon input-group-icon-right">
                                            <span class="input-icon input-icon-right"><i class="fa fa-calendar-check-o"></i></span>
                                            <input class="form-control form-control-line datepicker date" id="new-event-end" type="text" name="end" value="">
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group mb-4 pt-3">
                                    <label class="ui-switch switch-icon mr-3 mb-0">
                                        <input id="new-event-allDay" type="checkbox" checked>
                                        <span></span>
                                    </label>All Day</div>
                            </div>
                            <div class="modal-footer justify-content-start bg-primary-50">
                                <button class="btn btn-primary btn-rounded" id="addEventButton" type="submit">Add event</button>
                            </div>
                        </form>
                    </div>
                </div>
                <!-- End New Event Dialog-->
                <!-- Event Detail Dialog-->
                <div class="modal fade" id="event-modal" tabindex="-1" role="dialog">
                    <div class="modal-dialog" role="document">
                        <form class="modal-content form-horizontal" id="eventForm" action="javascript:;">
                            <div class="modal-header p-4">
                                <h5 class="modal-title">EDIT EVENT</h5>
                                <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">×</span>
                                </button>
                            </div>
                            <div class="modal-body p-4">
                                <div class="form-group mb-4">
                                    <label class="text-muted mb-3">Category</label>
                                    <div>
                                        <label class="radio radio-outline-primary radio-inline check-single" data-toggle="tooltip" data-original-title="General">
                                            <input type="radio" name="category" checked value="fc-event-primary">
                                            <span class="input-span"></span>
                                        </label>
                                        <label class="radio radio-outline-warning radio-inline check-single" data-toggle="tooltip" data-original-title="Payment">
                                            <input type="radio" name="category" value="fc-event-warning">
                                            <span class="input-span"></span>
                                        </label>
                                        <label class="radio radio-outline-success radio-inline check-single" data-toggle="tooltip" data-original-title="Technical">
                                            <input type="radio" name="category" value="fc-event-success">
                                            <span class="input-span"></span>
                                        </label>
                                        <label class="radio radio-outline-danger radio-inline check-single" data-toggle="tooltip" data-original-title="Registration">
                                            <input type="radio" name="category" value="fc-event-danger">
                                            <span class="input-span"></span>
                                        </label>
                                        <label class="radio radio-outline-info radio-inline check-single" data-toggle="tooltip" data-original-title="Security">
                                            <input type="radio" name="category" value="fc-event-info">
                                            <span class="input-span"></span>
                                        </label>
                                    </div>
                                </div>
                                <div class="form-group mb-4">
                                    <input class="form-control form-control-line" id="event-title" type="text" name="title" placeholder="Title">
                                </div>
                                <div class="row">
                                    <div class="col-6 form-group mb-4">
                                        <label class="col-form-label text-muted">Start:</label>
                                        <div class="input-group-icon input-group-icon-right">
                                            <span class="input-icon input-icon-right"><i class="fa fa-calendar-check-o"></i></span>
                                            <input class="form-control form-control-line datepicker date" id="event-start" type="text" name="start" value="">
                                        </div>
                                    </div>
                                    <div class="col-6 form-group mb-4">
                                        <label class="col-form-label text-muted">End:</label>
                                        <div class="input-group-icon input-group-icon-right">
                                            <span class="input-icon input-icon-right"><i class="fa fa-calendar-check-o"></i></span>
                                            <input class="form-control form-control-line datepicker date" id="event-end" type="text" name="end" value="">
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group mb-4 pt-3">
                                    <label class="ui-switch switch-icon mr-3 mb-0">
                                        <input id="event-allDay" type="checkbox">
                                        <span></span>
                                    </label>All Day</div>
                            </div>
                            <div class="modal-footer justify-content-between bg-primary-50">
                                <button class="btn btn-primary btn-rounded" id="editEventButton" type="submit">Submit</button>
                                <a class="text-danger" id="deleteEventButton" data-dismiss="modal"><i class="la la-trash font-20"></i></a>
                            </div>
                        </form>
                    </div>
                </div>
                <!-- End Event Detail Dialog-->
            </div>
            <!-- END PAGE CONTENT-->
            <?php echo $footer ?>
        </div>
    </div>
    <?php echo $commonJs ?>
        <!-- PAGE LEVEL PLUGINS-->
    <script src="<?php echo base_url() ?>ui/assets/plugins/moment/min/moment.min.js"></script>
    <script src="<?php echo base_url() ?>ui/assets/plugins/fullcalendar/dist/fullcalendar.min.js"></script>
    <script src="<?php echo base_url() ?>ui/assets/plugins/smalot-bootstrap-datetimepicker/js/bootstrap-datetimepicker.min.js"></script>
    <script src="<?php echo base_url() ?>ui/assets/plugins/jquery-ui/jquery-ui.min.js"></script>
    <script src="<?php echo base_url() ?>ui/assets/js/calendar.js"></script>
</body>
</html>
  