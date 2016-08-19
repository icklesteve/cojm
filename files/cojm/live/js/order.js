//     COJM Courier Online Operations Management
//     order.js - Javascript File only used in order.php , the main edit job page
//     Copyright (C) 2016 S.Young cojm.co.uk
//
//    This program is free software: you can redistribute it and/or modify
//    it under the terms of the GNU Affero General Public License as
//    published by the Free Software Foundation, either version 3 of the
//    License, or (at your option) any later version.
//
//    This program is distributed in the hope that it will be useful,
//    but WITHOUT ANY WARRANTY; without even the implied warranty of
//    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
//    GNU Affero General Public License for more details.
//
//    You should have received a copy of the GNU Affero General Public License
//    along with this program.  If not, see <http://www.gnu.org/licenses/>.

var $;
var id;
var message;
var oktosubmit;
var allok;
var oldclientorder;
var olddeporder;
var initialstatus;
var initialtargetcollectiondate;
var initialdeliveryworkingwindow;
var initialduedate;
var initialstarttrackpause;
var initialfinishtrackpause;
var initialstarttravelcollectiontime;
var initialwaitingstarttime;
var initialcollectionworkingwindow;
var initialcollectiondate;
var initialShipDate;
var initialjobrequestedtime;
var initialjobcomments;
var initialprivatejobcomments;
var initialrequestor;
var initialclientjobreference;
var waitingtimedelay;
var waitingmins;
var haspod;
var podsurname;
var formbirthday;
var showmessage;
var statustoohigh;
var publictrackingref;


$(function () { // Document is ready
    "use strict";


    function process(date) {
//        var datetime = date.split(" ");
//        var parts = datetime[0].split("/");
//        var timeparts = datetime[1].split(":");
        var newdate = new Date(date.split(" ")[0].split("/")[2], date.split(" ")[0].split("/")[1] - 1, date.split(" ")[0].split("/")[0], date.split(" ")[1].split(":")[0], date.split(" ")[1].split(":")[1]);
        return newdate;
    }


    function showhidebystatus() { // or changes to time values for showing buttons for working windows

        if (olddeporder < 1) {
            $("div#clientdep.fsr input.ui-autocomplete-input").addClass("autoinputerror").removeClass("");
        } else {
            $("div#clientdep.fsr input.ui-autocomplete-input").addClass("").removeClass("autoinputerror");
        }



        if (initialstatus < 31) {
            $("#starttravelcollectiontimediv").hide();
        } else {
            $("#starttravelcollectiontimediv").show();
        }


        if (initialstatus < 49) {
            $("#waitingstarttimediv").hide();
        } else {
            $("#waitingstarttimediv").show();
        }


        if (initialstatus < 59) {
            $("#collectiondatediv").hide();
        } else {
            $("#collectiondatediv").show();
        }


        if (initialstatus < 69) {
            $("#ShipDatediv").hide();
        } else {
            $("#ShipDatediv").show();
        }



        if (initialstatus < 100 && initialdeliveryworkingwindow === "") {
            $("#allowdww").show();
        } else {
            $("#allowdww").hide();
        }




        if (initialstatus < 100 && initialcollectionworkingwindow === "") {
            $("#allowww").show();
        } else {
            $("#allowww").hide();
        }


        if (initialdeliveryworkingwindow !== "") {
            $("#untildww").show();
            $("#deliveryworkingwindow").show();
        } else {
            $("#untildww").hide();
            $("#deliveryworkingwindow").hide();
        }


        if (initialcollectionworkingwindow !== "") {
            $("#allowwwuntil").show();
            $("#collectionworkingwindow").show();
        } else {
            $("#allowwwuntil").hide();
            $("#collectionworkingwindow").hide();
        }



        if ((initialstatus > 99) && (initialjobcomments !== 1)) {
            $("#jobcommentsdiv").hide();
        } else {
            $("#jobcommentsdiv").show();
        }


        if ((initialstatus > 99) && (initialprivatejobcomments !== 1)) {
            $("#privatejobcommentsdiv").hide();
        } else {
            $("#privatejobcommentsdiv").show();
        }


        if (initialstatus > 99) {
            $("#toggleresumechoose").hide();
        }


        if (initialstatus > 99) {
            $(".deleteord").hide();
        } else {
            $(".deleteord").show();
        }



        if (initialstatus > 99) {
            var subarea = $("#opsmapsubarea").val();
            if (subarea === "") {
                $("#opsmapsubarea").hide();
            } else {
                $("#opsmapsubarea").show();
            }
        } else {
            $("#opsmapsubarea").show();
        }


        if (initialstatus < 31) {
            $("#currorsched").hide();
        }



        if ((initialstarttrackpause !== "") || (initialfinishtrackpause !== "")) {
            $("#toggleresumechoose").hide();
        }




        if ((initialstatus > 99 && initialstarttravelcollectiontime === "") || (initialstatus < 40)) {
            $("#starttravelcollectiontimediv").hide();
        } else {
            $("#starttravelcollectiontimediv").show();
        }

        if ((initialstatus > 99 && initialwaitingstarttime === "") || (initialstatus < 50)) {
            $("#waitingstarttimediv").hide();
        } else {
            $("#waitingstarttimediv").show();
        }


        if (initialstatus > 99 && initialrequestor === "") {
            $("#requestordiv").hide();
        } else {
            $("#requestordiv").show();
        }



        if (initialstatus > 99 && initialclientjobreference === "") {
            $("#clientjobreferencediv").hide();
        } else {
            $("#clientjobreferencediv").show();
        }




        if (initialstatus > 99) {
            $("#buttoncancelpricelock").addClass("buttoncancelpricelocklocked");
        } else {
            $("#buttoncancelpricelock").removeClass("buttoncancelpricelocklocked");
        }





        if (initialstatus > 99) {
            $("#ajaxremovepod").hide();
            $("#uploadpodfile").hide();
        }


    } // ends showhidebystatus



    function testtimes() {
        showhidebystatus(); // updates which buttons to show
// if existing message start a new line
        if (message !== "") {
            message += "<br />";
        }
        oktosubmit = 1;

        if (initialtargetcollectiondate === "") {
            message += "No Target Collection Time <br /> ";
            oktosubmit = 0;
        }

        if (initialduedate === "") {
            message += "No Target Delivery Time <br /> ";
            oktosubmit = 0;
        }

        if (initialcollectiondate !== "") {
            if (initialShipDate !== "") {
                if (process(initialcollectiondate) > process(initialShipDate)) {
                    message += "Collection (" + initialcollectiondate + ") needs to be earlier than delivery (" + initialShipDate + ") <br /> ";
                    oktosubmit = 0;
                }
            }
        }


        if (initialduedate !== "") {
            if (initialdeliveryworkingwindow !== "") {
                if (process(initialdeliveryworkingwindow) < process(initialduedate)) {
                    message += "Delivery window start (" + initialduedate + ") needs to be earlier than Delivery window finish (" + initialdeliveryworkingwindow + ") <br />";
                    oktosubmit = 0;
                }
            }
        }


        if (initialcollectionworkingwindow !== "") {
            if (initialtargetcollectiondate !== "") {
                if (process(initialcollectionworkingwindow) < process(initialtargetcollectiondate)) {
                    message += "Collection window start (" + initialtargetcollectiondate + ") needs to be earlier than Collection window finish (" + initialcollectionworkingwindow + ") <br />";
                    oktosubmit = 0;
                }
            }
        }


        if (initialstarttrackpause !== "") {
            if (initialfinishtrackpause !== "") {
                if (process(initialstarttrackpause) > process(initialfinishtrackpause)) {
                    message += "Pause (" + initialstarttrackpause + ") needs to be earlier than Resume (" + initialfinishtrackpause + ") <br />";
                    oktosubmit = 0;
                }
            }
        }


        if (initialstarttrackpause !== "") {
            if (initialcollectiondate !== "") {
                if (process(initialcollectiondate) > process(initialstarttrackpause)) {
                    message += 'Collection (' + initialcollectiondate + ') needs to be earlier than Pause (' + initialstarttrackpause + ') <br />';
                    oktosubmit = 0;
                }
            }
        }


        if (initialfinishtrackpause !== "") {
            if (initialShipDate !== "") {
                if (process(initialfinishtrackpause) > process(initialShipDate)) {
                    message += 'Resume (' + initialfinishtrackpause + ') needs to be earlier than Complete (' + initialShipDate + ') <br />';
                    oktosubmit = 0;
                }
            }
        }



        if (initialstarttrackpause === "") {
            if (initialfinishtrackpause !== "") {
                message += 'Resumed but no Paused time<br />';
                oktosubmit = 0;
            }
        }


        if (initialwaitingstarttime !== "") {
            if (initialcollectiondate !== "") {
                if (process(initialcollectiondate) < process(initialwaitingstarttime)) {
                    message += 'En Site at PU (' + initialwaitingstarttime + ') needs to be earlier than Collection (' + initialcollectiondate + ') <br />';
                    oktosubmit = 0;
                }
            }
        }



        if (initialwaitingstarttime !== "") {
            if (initialstarttravelcollectiontime !== "") {
                if (process(initialstarttravelcollectiontime) > process(initialwaitingstarttime)) {
                    message += 'En Route to PU (' + initialstarttravelcollectiontime + ') needs to be earlier than On Site (' + initialwaitingstarttime + ') <br />';
                    oktosubmit = 0;
                }
            }
        }


        if (initialcollectiondate !== "") {
            if (initialstarttravelcollectiontime !== "") {
                if (process(initialstarttravelcollectiontime) > process(initialcollectiondate)) {
                    message += 'En Route to PU (' + initialstarttravelcollectiontime + ') needs to be earlier than Collection (' + initialcollectiondate + ') <br />';
                    oktosubmit = 0;
                }
            }
        }


        if (initialwaitingstarttime !== "") {
            if (initialcollectiondate !== "") {
                var waitingminstest = parseInt((process(initialcollectiondate) - process(initialwaitingstarttime) / 1000 / 60), 10);
                if (waitingminstest > waitingtimedelay) { // global test minutes more than than diff so see if waiting mins in cbb
                    if (waitingmins < 4) {

                        message += waitingminstest + 'mins from en site to collection with no waiting time in dropdown <br />';
                        // message+=waitingmins + 'mins waiting time selected in dropdown <br />';
                        oktosubmit = 0;
                    }
                }
            }
        }



        if (initialduedate !== "") {
            if (initialtargetcollectiondate !== "") {
                if (process(initialduedate) < process(initialtargetcollectiondate)) {
                    message += 'Target Collection  (' + initialtargetcollectiondate + ') needs to be earlier than Target Delivery (' + initialduedate + ') <br />';
                    oktosubmit = 0;
                }
            }
        }




        if (haspod === 1) {
            if (podsurname === "") {
                message += 'Needs POD Surname <br />';
                oktosubmit = 0;
            }
        }



        if (initialstatus > 59) {
            if (initialcollectiondate === "") {
                message += 'Needs PU Time <br />';
                oktosubmit = 0;
            }
        }


        if (initialstatus > 85) {
            if (initialShipDate === "") {
                message += 'Needs Delivery Time <br />';
                oktosubmit = 0;
            }
        }

        if (initialstatus > 61) {
            if (initialstarttrackpause !== "") {
                if (initialfinishtrackpause === "") {
                    message += 'Needs Resume Time <br />';
                    oktosubmit = 0;
                }
            }
        }


        // if ( oktosubmit==1 )  { } else { allok=0; }

    } // ends check time function




    function ordermapupdater() {
        $("#spinner").show();
        showhidebystatus();
        $.ajax({
            url: 'ajaxordermap.php',
            data: {
                page: 'ajaxclientjobreference',
                formbirthday: formbirthday,
                id: id
            },
            type: 'post',
            success: function (data) {
                $('#orderajaxmap').html(data);
            },
            complete: function () {
                $("#spinner").hide();
            }
        });
    }




    function editnewcost() {
        var newcost = $("#newcost").val();
        $.ajax({
            url: 'ajaxchangejob.php', //Server script to process data
            data: {
                page: 'ajaxeditcost',
                formbirthday: formbirthday,
                id: id,
                newcost: newcost
            },
            type: 'post',
            success: function (data) {
                $('#emissionsaving').append(data);
            },
            complete: function () {
                showmessage();
                resizenewcost();
            }
        });
    }



    $("#newcost").change(function () {
        editnewcost();
    });

    $("#jschangfavto").bind("click", function (e) {
        e.preventDefault();
        $.zebra_dialog("", {
            "source": {
                "ajax": ("ajaxselectfav.php?addr=to&clientid=" + oldclientorder + "&jobid=" + id)
            },
            "type": "question",
            width: 500,
            position: ["left + 20", "top + 30"],
            "title": "Please select new address",
            "buttons": [{
                caption: "Cancel"
            }, {
                caption: "Select",
                callback: function () {
                    $("#selectfav").submit();
                }
            }]
        });
    });

    $("#jschangfavvia1").bind("click", function (e) {
        e.preventDefault();
        $.zebra_dialog("", {
            "source": {
                "ajax": ("ajaxselectfav.php?addr=via1&clientid=" + oldclientorder + "&jobid=" + id)
            },
            "type": "question",
            "width": "500",
            position: ["left + 20", "top + 30"],
            "title": "Please select new address",
            "buttons": [{
                caption: "Cancel"
            }, {
                caption: "Select",
                callback: function () {
                    $("#selectfav").submit();
                }
            }]
        });
    });

    $("#jschangfavvia2").bind("click", function (e) {
        e.preventDefault();
        $.zebra_dialog("", {
            "source": {
                "ajax": ("ajaxselectfav.php?addr=via2&clientid=" + oldclientorder + "&jobid=" + id)
            },
            "type": "question",
            "width": "500",
            position: ["left + 20", "top + 30"],
            "title": "Please select new address",
            "buttons": [{
                caption: "Cancel"
            }, {
                caption: "Select",
                callback: function () {
                    $("#selectfav").submit();
                }
            }]
        });
    });
    $("#jschangfavvia3").bind("click", function (e) {
        e.preventDefault();
        $.zebra_dialog("", {
            "source": {
                'ajax': ('ajaxselectfav.php?addr=via3&clientid=' + oldclientorder + '&jobid=' + id)
            },
            'type': 'question',
            'width': '500',
            position: ['left + 20', 'top + 30'],
            'title': 'Please select new address',
            'buttons': [{
                caption: 'Cancel'
            }, {
                caption: 'Select',
                callback: function () {
                    $("#selectfav").submit();
                }
            }]
        });
    });
    $('#jschangfavvia4').bind('click', function (e) {
        e.preventDefault();
        $.zebra_dialog('', {
            'source': {
                'ajax': ('ajaxselectfav.php?addr=via4&clientid=' + oldclientorder + '&jobid=' + id)
            },
            'type': 'question',
            'width': '500',
            position: ['left + 20', 'top + 30'],
            'title': 'Please select new address',
            'buttons': [{
                caption: 'Cancel'
            }, {
                caption: 'Select',
                callback: function () {
                    $("#selectfav").submit();
                }
            }]
        });
    });
    $('#jschangfavvia5').bind('click', function (e) {
        e.preventDefault();
        $.zebra_dialog('', {
            'source': {
                'ajax': ('ajaxselectfav.php?addr=via5&clientid=' + oldclientorder + '&jobid=' + id)
            },
            'type': 'question',
            'width': '500',
            position: ['left + 20', 'top + 30'],
            'title': 'Please select new address',
            'buttons': [{
                caption: 'Cancel'
            }, {
                caption: 'Select',
                callback: function () {
                    $("#selectfav").submit();
                }
            }]
        });
    });
    $('#jschangfavvia6').bind('click', function (e) {
        e.preventDefault();
        $.zebra_dialog('', {
            'source': {
                'ajax': ('ajaxselectfav.php?addr=via6&clientid=' + oldclientorder + '&jobid=' + id)
            },
            'type': 'question',
            'width': '500',
            position: ['left + 20', 'top + 30'],
            'title': 'Please select new address',
            'buttons': [{
                caption: 'Cancel'
            }, {
                caption: 'Select',
                callback: function () {
                    $("#selectfav").submit();
                }
            }]
        });
    });
    $('#jschangfavvia7').bind('click', function (e) {
        e.preventDefault();
        $.zebra_dialog('', {
            'source': {
                'ajax': ('ajaxselectfav.php?addr=via7&clientid=' + oldclientorder + '&jobid=' + id)
            },
            'type': 'question',
            'width': '500',
            position: ['left + 20', 'top + 30'],
            'title': 'Please select new address',
            'buttons': [{
                caption: 'Cancel'
            }, {
                caption: 'Select',
                callback: function () {
                    $("#selectfav").submit();
                }
            }]
        });
    });
    $('#jschangfavvia8').bind('click', function (e) {
        e.preventDefault();
        $.zebra_dialog('', {
            'source': {
                'ajax': ('ajaxselectfav.php?addr=via8&clientid=' + oldclientorder + '&jobid=' + id)
            },
            'type': 'question',
            'width': '500',
            position: ['left + 20', 'top + 30'],
            'title': 'Please select new address',
            'buttons': [{
                caption: 'Cancel'
            }, {
                caption: 'Select',
                callback: function () {
                    $("#selectfav").submit();
                }
            }]
        });
    });
    $('#jschangfavvia9').bind('click', function (e) {
        e.preventDefault();
        $.zebra_dialog('', {
            'source': {
                'ajax': ('ajaxselectfav.php?addr=via9&clientid=' + oldclientorder + '&jobid=' + id)
            },
            'type': 'question',
            'width': '500',
            position: ['left + 20', 'top + 30'],
            'title': 'Please select new address',
            'buttons': [{
                caption: 'Cancel'
            }, {
                caption: 'Select',
                callback: function () {
                    $("#selectfav").submit();
                }
            }]
        });
    });
    $('#jschangfavvia10').bind('click', function (e) {
        e.preventDefault();
        $.zebra_dialog('', {
            'source': {
                'ajax': ('ajaxselectfav.php?addr=via10&clientid=' + oldclientorder + '&jobid=' + id)
            },
            'type': 'question',
            'width': '500',
            position: ['left + 20', 'top + 30'],
            'title': 'Please select new address',
            'buttons': [{
                caption: 'Cancel'
            }, {
                caption: 'Select',
                callback: function () {
                    $("#selectfav").submit();
                }
            }]
        });
    });
    $('#jschangfavvia11').bind('click', function (e) {
        e.preventDefault();
        $.zebra_dialog('', {
            'source': {
                'ajax': ('ajaxselectfav.php?addr=via11&clientid=' + oldclientorder + '&jobid=' + id)
            },
            'type': 'question',
            'width': '500',
            position: ['left + 20', 'top + 30'],
            'title': 'Please select new address',
            'buttons': [{
                caption: 'Cancel'
            }, {
                caption: 'Select',
                callback: function () {
                    $("#selectfav").submit();
                }
            }]
        });
    });
    $('#jschangfavvia12').bind('click', function (e) {
        e.preventDefault();
        $.zebra_dialog('', {
            'source': {
                'ajax': ('ajaxselectfav.php?addr=via12&clientid=' + oldclientorder + '&jobid=' + id)
            },
            'type': 'question',
            'width': '500',
            position: ['left + 20', 'top + 30'],
            'title': 'Please select new address',
            'buttons': [{
                caption: 'Cancel'
            }, {
                caption: 'Select',
                callback: function () {
                    $("#selectfav").submit();
                }
            }]
        });
    });
    $('#jschangfavvia13').bind('click', function (e) {
        e.preventDefault();
        $.zebra_dialog('', {
            'source': {
                'ajax': ('ajaxselectfav.php?addr=via13&clientid=' + oldclientorder + '&jobid=' + id)
            },
            'type': 'question',
            'width': '500',
            position: ['left + 20', 'top + 30'],
            'title': 'Please select new address',
            'buttons': [{
                caption: 'Cancel'
            }, {
                caption: 'Select',
                callback: function () {
                    $("#selectfav").submit();
                }
            }]
        });
    });
    $('#jschangfavvia14').bind('click', function (e) {
        e.preventDefault();
        $.zebra_dialog('', {
            'source': {
                'ajax': ('ajaxselectfav.php?addr=via14&clientid=' + oldclientorder + '&jobid=' + id)
            },
            'type': 'question',
            'width': '500',
            position: ['left + 20', 'top + 30'],
            'title': 'Please select new address',
            'buttons': [{
                caption: 'Cancel'
            }, {
                caption: 'Select',
                callback: function () {
                    $("#selectfav").submit();
                }
            }]
        });
    });
    $('#jschangfavvia15').bind('click', function (e) {
        e.preventDefault();
        $.zebra_dialog('', {
            'source': {
                'ajax': ('ajaxselectfav.php?addr=via15&clientid=' + oldclientorder + '&jobid=' + id)
            },
            'type': 'question',
            'width': '500',
            position: ['left + 20', 'top + 30'],
            'title': 'Please select new address',
            'buttons': [{
                caption: 'Cancel'
            }, {
                caption: 'Select',
                callback: function () {
                    $("#selectfav").submit();
                }
            }]
        });
    });
    $('#jschangfavvia16').bind('click', function (e) {
        e.preventDefault();
        $.zebra_dialog('', {
            'source': {
                'ajax': ('ajaxselectfav.php?addr=via16&clientid=' + oldclientorder + '&jobid=' + id)
            },
            'type': 'question',
            'width': '500',
            position: ['left + 20', 'top + 30'],
            'title': 'Please select new address',
            'buttons': [{
                caption: 'Cancel'
            }, {
                caption: 'Select',
                callback: function () {
                    $("#selectfav").submit();
                }
            }]
        });
    });
    $('#jschangfavvia17').bind('click', function (e) {
        e.preventDefault();
        $.zebra_dialog('', {
            'source': {
                'ajax': ('ajaxselectfav.php?addr=via17&clientid=' + oldclientorder + '&jobid=' + id)
            },
            'type': 'question',
            'width': '500',
            position: ['left + 20', 'top + 30'],
            'title': 'Please select new address',
            'buttons': [{
                caption: 'Cancel'
            }, {
                caption: 'Select',
                callback: function () {
                    $("#selectfav").submit();
                }
            }]
        });
    });
    $('#jschangfavvia18').bind('click', function (e) {
        e.preventDefault();
        $.zebra_dialog('', {
            'source': {
                'ajax': ('ajaxselectfav.php?addr=via18&clientid=' + oldclientorder + '&jobid=' + id)
            },
            'type': 'question',
            'width': '500',
            position: ['left + 20', 'top + 30'],
            'title': 'Please select new address',
            'buttons': [{
                caption: 'Cancel'
            }, {
                caption: 'Select',
                callback: function () {
                    $("#selectfav").submit();
                }
            }]
        });
    });
    $('#jschangfavvia19').bind('click', function (e) {
        e.preventDefault();
        $.zebra_dialog('', {
            'source': {
                'ajax': ('ajaxselectfav.php?addr=via19&clientid=' + oldclientorder + '&jobid=' + id)
            },
            'type': 'question',
            'width': '500',
            position: ['left + 20', 'top + 30'],
            'title': 'Please select new address',
            'buttons': [{
                caption: 'Cancel'
            }, {
                caption: 'Select',
                callback: function () {
                    $("#selectfav").submit();
                }
            }]
        });
    });
    $('#jschangfavvia20').bind('click', function (e) {
        e.preventDefault();
        $.zebra_dialog('', {
            'source': {
                'ajax': ('ajaxselectfav.php?addr=via20&clientid=' + oldclientorder + '&jobid=' + id)
            },
            'type': 'question',
            'width': '500',
            position: ['left + 20', 'top + 30'],
            'title': 'Please select new address',
            'buttons': [{
                caption: 'Cancel'
            }, {
                caption: 'Select',
                callback: function () {
                    $("#selectfav").submit();
                }
            }]
        });
    });


    function checktargetcollectiondate() {
        if (initialstatus < 100) {
            var targetcollectiondate = $("#targetcollectiondate").val().trim();
            if (targetcollectiondate !== initialtargetcollectiondate) {
                initialtargetcollectiondate=targetcollectiondate;
                $.ajax({
                    url: 'ajaxchangejob.php', //Server script to process data
                    data: {
                        page: 'ajaxtargetcollectiondate',
                        formbirthday: formbirthday,
                        id: id,
                        targetcollectiondate: targetcollectiondate
                    },
                    type: 'post',
                    success: function (data) {
                        $('#emissionsaving').append(data);
                    },
                    complete: function () {
                        testtimes();
                        showmessage();
                    }
                });
            } // times different
        } else { // status too high
            allok = 0;
            message = statustoohigh;
            showmessage();
            $('#targetcollectiondate').val(initialtargetcollectiondate);
        }
    }



    function checkwaitingstarttime() {
        if (initialstatus < 100) {
            var waitingstarttime = $("#waitingstarttime").val().trim();
            if (waitingstarttime !== initialwaitingstarttime) {
                initialwaitingstarttime = waitingstarttime;
                $.ajax({
                    url: 'ajaxchangejob.php',
                    data: {
                        page: 'ajaxwaitingstarttime',
                        formbirthday: formbirthday,
                        id: id,
                        waitingstarttime: waitingstarttime
                    },
                    type: 'post',
                    success: function (data) {
                        $('#emissionsaving').append(data);
                    },
                    complete: function () {
                        testtimes();
                        showmessage();
                    }
                });
            } // times different
        } else {
            allok = 0;
            message = statustoohigh;
            showmessage();
            $('#waitingstarttime').val(initialwaitingstarttime);
        }
    }




    function checkcollectiondate() {
        if (initialstatus < 100) {
            var collectiondate = $("#collectiondate").val().trim();
            if (collectiondate !== initialcollectiondate) {
                initialcollectiondate = collectiondate;  // needed to stop firing twice on time change
                $("#spinner").show();
                $.ajax({
                    url: 'ajaxchangejob.php',
                    data: {
                        page: 'ajaxcollectiondate',
                        formbirthday: formbirthday,
                        id: id,
                        collectiondate: collectiondate
                    },
                    type: 'post',
                    success: function (data) {
                        $('#emissionsaving').append(data);
                    },
                    complete: function () {
                        testtimes();
                        showmessage();
                        ordermapupdater();
                    }
                });
            } // times different
        } else {
            allok = 0;
            message = statustoohigh;
            showmessage();
            $('#collectiondate').val(initialcollectiondate);
        }
    }




    function checkstarttrackpause() {
        if (initialstatus < 100) {
            var starttrackpause = $("#starttrackpause").val().trim();
            if (starttrackpause !== initialstarttrackpause) {
                initialstarttrackpause = starttrackpause;
                $.ajax({
                    url: 'ajaxchangejob.php',
                    data: {
                        page: 'ajaxstarttrackpause',
                        formbirthday: formbirthday,
                        id: id,
                        starttrackpause: starttrackpause
                    },
                    type: 'post',
                    success: function (data) {
                        $('#emissionsaving').append(data);
                    },
                    complete: function () {
                        testtimes();
                        showmessage();
                    }
                });
            } // times different
        } else {
            allok = 0;
            message = statustoohigh;
            showmessage();
            $('#starttrackpause').val(initialstarttrackpause);
        }
    }




    function checkfinishtrackpause() {
        if (initialstatus < 100) {
            var finishtrackpause = $("#finishtrackpause").val().trim();
            if (finishtrackpause !== initialfinishtrackpause) {
                initialfinishtrackpause = finishtrackpause;
                $.ajax({
                    url: 'ajaxchangejob.php',
                    data: {
                        page: 'ajaxfinishtrackpause',
                        formbirthday: formbirthday,
                        id: id,
                        finishtrackpause: finishtrackpause
                    },
                    type: 'post',
                    success: function (data) {
                        $('#emissionsaving').append(data);
                    },
                    complete: function () {
                        testtimes();
                        showmessage();
                    }
                });
            } // times different
        } else {
            allok = 0;
            message = statustoohigh;
            showmessage();
            $('#finishtrackpause').val(initialfinishtrackpause);
        }
    }




    function checkduedate() {
        if (initialstatus < 100) {
            var duedate = $("#duedate").val().trim();
            if (duedate !== initialduedate) {
                
                initialduedate = duedate;
                
                $.ajax({
                    url: "ajaxchangejob.php",
                    data: {
                        page: "ajaxduedate",
                        formbirthday: formbirthday,
                        id: id,
                        duedate: duedate
                    },
                    type: "post",
                    success: function (data) {
                        $("#emissionsaving").append(data);
                    },
                    complete: function () {
                        testtimes();
                        showmessage();
                    }
                });
            } // times different
        } else {
            allok = 0;
            message = statustoohigh;
            showmessage();
            $('#duedate').val(initialduedate);
        }
    }



    function checkdeliveryworkingwindow() {
        if (initialstatus < 100) {
            var deliveryworkingwindow = $("#deliveryworkingwindow").val().trim();
            if (deliveryworkingwindow !== initialdeliveryworkingwindow) {
                initialdeliveryworkingwindow = deliveryworkingwindow;
                $.ajax({
                    url: 'ajaxchangejob.php',
                    data: {
                        page: 'ajaxdeliveryworkingwindow',
                        formbirthday: formbirthday,
                        id: id,
                        deliveryworkingwindow: deliveryworkingwindow
                    },
                    type: 'post',
                    success: function (data) {
                        $('#emissionsaving').append(data);
                    },
                    complete: function () {
                        testtimes();
                        showmessage();
                    }
                });
            } // times different
        } else {
            allok = 0;
            message = statustoohigh;
            showmessage();
            $('#deliveryworkingwindow').val(initialdeliveryworkingwindow);
        }
    }


    function checkShipDate() {
        if (initialstatus < 100) {
            var ShipDate = $("#ShipDate").val().trim();
            if (ShipDate !== initialShipDate) {
                initialShipDate = ShipDate;  // needed to stop firing twice on time change
                $("#spinner").show();
                $.ajax({
                    url: 'ajaxchangejob.php', //Server script to process data
                    data: {
                        page: 'ajaxShipDate',
                        formbirthday: formbirthday,
                        id: id,
                        ShipDate: ShipDate
                    },
                    type: 'post',
                    success: function (data) {
                        $('#emissionsaving').append(data);
                    },
                    complete: function () {
                        ordermapupdater();
                        testtimes();
                        showmessage();
                    }
                });
            } // times different
        } else {
            allok = 0;
            message = statustoohigh;
            showmessage();
            $('#ShipDate').val(initialShipDate);
        }
    }



    function checkjobrequestedtime() {
        if (initialstatus < 100) {
            var jobrequestedtime = $("#jobrequestedtime").val().trim();
            if (jobrequestedtime !== initialjobrequestedtime) {
                initialjobrequestedtime = jobrequestedtime;
                $.ajax({
                    url: 'ajaxchangejob.php',
                    data: {
                        page: 'ajaxjobrequestedtime',
                        formbirthday: formbirthday,
                        id: id,
                        jobrequestedtime: jobrequestedtime
                    },
                    type: 'post',
                    success: function (data) {
                        $('#emissionsaving').append(data);
                    },
                    complete: function () {
                        testtimes();
                        showmessage();
                    }
                });
            } // times different
        } else {
            allok = 0;
            message = statustoohigh;
            showmessage();
            $('#jobrequestedtime').val(initialjobrequestedtime);
        }
    }



    $(function () {
        $("#targetcollectiondate").datetimepicker({
            numberOfMonths: 1,
            changeYear: false,
            firstDay: 1,
            addSliderAccess: true,
            sliderAccessArgs: {
                touchonly: false
            },
            dateFormat: "dd/mm/yy",
            timeFormat: "hh:mm",
            changeMonth: false,
            onClose: function () {
                checktargetcollectiondate();
            }
        });
    });


    $(function () {
        $('#targetcollectiondate').on('blur', function () {
            checktargetcollectiondate();
        });
    });



    function checkcollectionworkingwindow() {
        if (initialstatus < 100) {
            var collectionworkingwindow = $("#collectionworkingwindow").val().trim();
            if (collectionworkingwindow !== initialcollectionworkingwindow) {
                initialcollectionworkingwindow = collectionworkingwindow;
                $.ajax({
                    url: 'ajaxchangejob.php', //Server script to process data
                    data: {
                        page: 'ajaxcollectionworkingwindow',
                        formbirthday: formbirthday,
                        id: id,
                        collectionworkingwindow: collectionworkingwindow
                    },
                    type: 'post',
                    success: function (data) {
                        $('#emissionsaving').append(data);
                    },
                    complete: function () {
                        testtimes();
                        showmessage();
                    }
                });
            } // times different
        } else {
            allok = 0;
            message = statustoohigh;
            showmessage();
            $('#collectionworkingwindow').val(initialcollectionworkingwindow);
        }
    }




    $(function () {
        $("#collectionworkingwindow").datetimepicker({
            numberOfMonths: 1,
            changeYear: false,
            firstDay: 1,
            addSliderAccess: true,
            sliderAccessArgs: {
                touchonly: false
            },
            dateFormat: "dd/mm/yy",
            timeFormat: "hh:mm",
            changeMonth: false,
            beforeShow: function () {
                if (initialcollectionworkingwindow === "") {
                    $("#collectionworkingwindow").val(initialtargetcollectiondate);
                }
            },
            onClose: function () {
                checkcollectionworkingwindow();
            }

        });
    });



    function checkstarttravelcollectiontime() {
        if (initialstatus < 100) {
            var starttravelcollectiontime = $("#starttravelcollectiontime").val().trim();
            if (starttravelcollectiontime !== initialstarttravelcollectiontime) {
                initialstarttravelcollectiontime = starttravelcollectiontime;
                $.ajax({
                    url: 'ajaxchangejob.php',
                    data: {
                        page: 'ajaxstarttravelcollectiontime',
                        formbirthday: formbirthday,
                        id: id,
                        starttravelcollectiontime: starttravelcollectiontime
                    },
                    type: 'post',
                    success: function (data) {
                        $('#emissionsaving').append(data);
                    },
                    complete: function () {
                        testtimes();
                        showmessage();
                    }
                });
            } // times different
        } else {
            allok = 0;
            message = statustoohigh;
            showmessage();
            $('#starttravelcollectiontime').val(initialstarttravelcollectiontime);
        }
    }



    $(function () {
        $("#starttravelcollectiontime").datetimepicker({
            numberOfMonths: 1,
            changeYear: false,
            firstDay: 1,
            addSliderAccess: true,
            sliderAccessArgs: {
                touchonly: false
            },
            dateFormat: "dd/mm/yy",
            timeFormat: "hh:mm",
            changeMonth: false,
            onClose: function () {
                checkstarttravelcollectiontime();
            }
        });
    });




    $(function () {
        $("#waitingstarttime").datetimepicker({
            numberOfMonths: 1,
            changeYear: false,
            firstDay: 1,
            addSliderAccess: true,
            sliderAccessArgs: {
                touchonly: false
            },
            dateFormat: "dd/mm/yy",
            timeFormat: "hh:mm",
            changeMonth: false,
            onClose: function () {
                checkwaitingstarttime();
            }
        });
    });


    $(function () {
        $("#collectiondate").datetimepicker({
            numberOfMonths: 1,
            changeYear: false,
            firstDay: 1,
            addSliderAccess: true,
            sliderAccessArgs: {
                touchonly: false
            },
            dateFormat: "dd/mm/yy",
            timeFormat: "hh:mm",
            changeMonth: false,
            onClose: function () {
                checkcollectiondate();
            }
        });
    });


    $(function () {
        $("#starttrackpause").datetimepicker({
            numberOfMonths: 1,
            changeYear: false,
            firstDay: 1,
            addSliderAccess: true,
            sliderAccessArgs: {
                touchonly: false
            },
            dateFormat: "dd/mm/yy",
            timeFormat: "hh:mm",
            changeMonth: false,
            beforeShow: function () {
                if (initialstarttrackpause === "") {
                    // alert(" delivery ww not set, adding main target collection ");
                    $("#starttrackpause").val(initialcollectiondate);
                }
            },
            onClose: function () {
                checkstarttrackpause();
            }
        });
    });



    $(function () {
        $("#finishtrackpause").datetimepicker({
            numberOfMonths: 1,
            changeYear: false,
            firstDay: 1,
            addSliderAccess: true,
            sliderAccessArgs: {
                touchonly: false
            },
            dateFormat: "dd/mm/yy",
            timeFormat: "hh:mm",
            beforeShow: function () {
                if (initialfinishtrackpause === "") {
                    if (initialShipDate !== "") {
                        $("#finishtrackpause").val(initialShipDate);
                    } else if (initialstarttrackpause !== "") {
                        $("#finishtrackpause").val(initialstarttrackpause);
                    } else if (initialcollectiondate !== "") {
                        $("#finishtrackpause").val(initialcollectiondate);
                    }
                }
            },
            changeMonth: false,
            onClose: function () {
                checkfinishtrackpause();
            }
        });
    });



    $(function () {
        $("#duedate").datetimepicker({
            numberOfMonths: 1,
            changeYear: false,
            firstDay: 1,
            addSliderAccess: true,
            sliderAccessArgs: {
                touchonly: false
            },
            dateFormat: "dd/mm/yy",
            timeFormat: "hh:mm",
            changeMonth: false,
            onClose: function () {
                checkduedate();
            }
        });
    });



    $(function () {
        $("#deliveryworkingwindow").datetimepicker({
            numberOfMonths: 1,
            changeYear: false,
            firstDay: 1,
            addSliderAccess: true,
            sliderAccessArgs: {
                touchonly: false
            },
            dateFormat: "dd/mm/yy",
            timeFormat: "hh:mm",
            beforeShow: function () {
                if (initialdeliveryworkingwindow === "") {
                    // alert(" delivery ww not set, adding main target collection ");
                    $("#deliveryworkingwindow").val(initialduedate);
                }
            },
            onClose: function () {
                checkdeliveryworkingwindow();
            },
            changeMonth: false
        });
    });


    $(function () {
        $("#ShipDate").datetimepicker({
            numberOfMonths: 1,
            changeYear: false,
            firstDay: 1,
            addSliderAccess: true,
            sliderAccessArgs: {
                touchonly: false
            },
            dateFormat: "dd/mm/yy",
            timeFormat: "hh:mm",
            onClose: function () {
                checkShipDate();
            },
            changeMonth: false
        });
    });



    $(function () {
        $("#jobrequestedtime").datetimepicker({
            numberOfMonths: 1,
            changeYear: false,
            firstDay: 1,
            addSliderAccess: true,
            sliderAccessArgs: {
                touchonly: false
            },
            dateFormat: "dd/mm/yy",
            timeFormat: "hh:mm",
            changeMonth: false,
            onClose: function () {
                checkjobrequestedtime();
            }
        });
    });

    function sendstatus() {
        var newstatus = $("select#newstatus").val();
        $.ajax({
            url: 'ajaxchangejob.php',  //Server script to process data
            data: {
                page: 'ajaxorderstatus',
                formbirthday: formbirthday,
                newstatus: newstatus,
                id: id
            },
            type: 'post',
            success: function (data) {
                $('#emissionsaving').append(data);
            },
            complete: function () {
                showmessage();
                ordermapupdater();
                showhidebystatus();
            }
        });
        // message=' Code to send status ';
    }



    function resizenewcost() {
    
//   alert(" about to resize ");
    
    var testsize=$("#newcost").val().length-1;
    
    $("#newcost").attr("style","");
    $("#newcost").attr("size", testsize);
}


    
    

    
    

    $("#newstatus").change(function () {
        message = '';
        testtimes();
        if (oktosubmit === 1) {
            sendstatus();
        } else {
            $("select#newstatus").val(initialstatus);
            allok = 0;
            showmessage();
        }
    });



    $("#podsurname").change(function () {
        podsurname = $("#podsurname").val();
        $.ajax({
            url: 'ajaxchangejob.php', //Server script to process data
            data: {
                page: 'ajaxpodsurname',
                formbirthday: formbirthday,
                id: id,
                podsurname: podsurname
            },
            type: 'post',
            success: function (data) {
                $('#emissionsaving').append(data);
            },
            complete: function () {
                showmessage();
            }
        });
    });




    $(".cbbcheckbox").change(function () {
        waitingmins = $("select#waitingmins").val();
        var cbbchecked;
        var cbbname = (this.name);
        if (this.checked) {
            cbbchecked = 1;
        } else {
            cbbchecked = 0;
        }
        $.ajax({
            url: 'ajaxchangejob.php', //Server script to process data
            data: {
                page: 'ajaxcbb',
                cbbname: cbbname,
                cbbchecked: cbbchecked,
                waitingmins: waitingmins,
                id: id,
                formbirthday: formbirthday
            },
            type: "post",
            success: function (data) {
                $("#emissionsaving").append(data);
            },
            complete: function () {
                showmessage();
            }
        });
    });





    $("#opsmaparea").change(function () {
        $("#spinner").show();
        var opsmaparea = $("select#opsmaparea").val();
        $.ajax({
            url: 'ajaxchangejob.php',
            data: {
                page: 'ajaxchangeopsmaparea',
                formbirthday: formbirthday,
                id: id,
                opsmaparea: opsmaparea
            },
            type: 'post',
            success: function (data) {
                $('#client').append(data);
            },
            complete: function () {
                ordermapupdater();
                showmessage();
            }
        });
    });




    $("#serviceid").change(function () {
        var serviceid = $("select#serviceid").val();
        $.ajax({
            url: 'ajaxchangejob.php',
            data: {
                page: 'ajaxchangeserviceid',
                formbirthday: formbirthday,
                id: id,
                serviceid: serviceid
            },
            type: 'post',
            success: function (data) {
                $('#client').append(data);
            },
            complete: function () {
                ordermapupdater();
                showmessage();
                resizenewcost();
            }
        });
    });




    $('#uploadpodfile').change(function () {

        function progressHandlingFunction(e) {
            if (e.lengthComputable) {
                $('progress').attr({
                    value: e.loaded,
                    max: e.total
                });
            }
        }



        $("#spinner").show();
        $('progress').attr({
            value: 0,
            max: 1
        });
        $('#uploadpodprogress').show();
        $("#formbirthday").val(formbirthday);


//            var file = this.files[0];
//            var name = file.name;
//            var size = file.size;
//            var type = file.type;
            // more validation

        var formData = new FormData($("#uploadpodform")[0]);
        $.ajax({
            url: "ajaxchangejob.php",
            type: "POST",
            xhr: function () { // Custom XMLHttpRequest
                var myXhr = $.ajaxSettings.xhr();
                if (myXhr.upload) { // Check if upload property exists
                    myXhr.upload.addEventListener('progress', progressHandlingFunction, false); // For handling the progress of the upload
                }
                return myXhr;
            },
            //        beforeSend: beforeSendHandler,
            success: function (data) {
                $('#emissionsaving').append(data);

            },
            complete: function () {
                showmessage();
                $("#spinner").hide();
            },

            //        error: errorHandler,
            // Form data
            data: formData,
            //Options to tell jQuery not to process data or worry about content-type.
            cache: false,
            contentType: false,
            processData: false
        });
    });



    $("#ajaxremovepod").click(function () {
        $.ajax({
            url: 'ajaxchangejob.php',
            data: {
                page: 'ajaxremovepod',
                formbirthday: formbirthday,
                id: id,
                publicid: publictrackingref
            },
            type: 'post',
            success: function (data) {
                $('#emissionsaving').append(data);
            },
            complete: function () {
                showmessage();
            }
        });
    });




    $("#opsmapsubarea").change(function () {
        $("#spinner").show();
        var opsmapsubarea = $("select#opsmapsubarea").val();
        $.ajax({
            url: 'ajaxchangejob.php',
            data: {
                page: 'ajaxchangeopsmapsubarea',
                formbirthday: formbirthday,
                id: id,
                opsmapsubarea: opsmapsubarea
            },
            type: 'post',
            success: function (data) {
                $('#client').append(data);
            },
            complete: function () {
                showmessage();
            }
        });
    });




    $("#jobcomments").change(function () {
        var jobcomments = $(this).val();
        $.ajax({
            url: 'ajaxchangejob.php',
            data: {
                page: 'ajaxjobcomments',
                formbirthday: formbirthday,
                id: id,
                jobcomments: jobcomments
            },
            type: 'post',
            success: function (data) {
                $('#emissionsaving').append(data);
            },
            complete: function () {
                showmessage();
            }
        });
    });



    $("#privatejobcomments").change(function () {
        var privatejobcomments = $(this).val();
        $.ajax({
            url: 'ajaxchangejob.php',
            data: {
                page: 'ajaxprivatejobcomments',
                formbirthday: formbirthday,
                id: id,
                privatejobcomments: privatejobcomments
            },
            type: 'post',
            success: function (data) {
                $('#emissionsaving').append(data);
            },
            complete: function () {
                showmessage();
            }
        });
    });




    $("#requestor").change(function () {
        var requestor = $(this).val();
        $.ajax({
            url: 'ajaxchangejob.php',
            data: {
                page: 'ajaxrequestor',
                formbirthday: formbirthday,
                id: id,
                requestor: requestor
            },
            type: 'post',
            success: function (data) {
                $('#emissionsaving').append(data);
            },
            complete: function () {
                showmessage();
            }
        });
    });




    $("#clientjobreference").change(function () {
        var clientjobreference = $(this).val();
        $.ajax({
            url: 'ajaxchangejob.php', //Server script to process data
            data: {
                page: 'ajaxclientjobreference',
                formbirthday: formbirthday,
                id: id,
                clientjobreference: clientjobreference
            },
            type: 'post',
            success: function (data) {
                $('#emissionsaving').append(data);
            },
            complete: function () {
                showmessage();
            }
        });
    });




    $("#numberitems").change(function () {
        $("#spinner").show();
        var numberitems = $(this).val();
        $.ajax({
            url: 'ajaxchangejob.php', //Server script to process data
            data: {
                page: 'ajaxnumberitems',
                formbirthday: formbirthday,
                id: id,
                numberitems: numberitems
            },
            type: 'post',
            success: function (data) {
                $('#emissionsaving').append(data);
            },
            complete: function () {
                showmessage();
                resizenewcost();
                $("#spinner").hide();
            }
        });
    });


    $(function () {
        $("#starttravelcollectiontime").on("blur", function () {
            checkstarttravelcollectiontime();
        });
    });

    $(function () {
        $("#waitingstarttime").on("blur", function () {
            checkwaitingstarttime();
        });
    });

    $(function () {
        $("#collectiondate").on("blur", function () {
            checkcollectiondate();
        });
    });

    $(function () {
        $("#starttrackpause").on("blur", function () {
            checkstarttrackpause();
        });
    });

    $(function () {
        $("#finishtrackpause").on("blur", function () {
            checkfinishtrackpause();
        });
    });

    $(function () {
        $("#duedate").on("blur", function () {
            checkduedate();
        });
    });

    $(function () {
        $("#deliveryworkingwindow").on("blur", function () {
            checkdeliveryworkingwindow();
        });
    });

    $(function () {
        $("#ShipDate").on("blur", function () {
            checkShipDate();
        });
    });

    $(function () {
        $("#jobrequestedtime").on("blur", function () {
            checkjobrequestedtime();
        });
    });



    $("#allowww").click(function () {
        $("#collectionworkingwindow").slideToggle("fast");
    });

    $("#allowdww").click(function () {
        $("#deliveryworkingwindow").slideToggle("fast");
    });

    $("#toggleresumechoose").click(function () {
        $("#toggleresume").slideToggle("fast");
        $("#toggleresumechoose").hide();
    });



    setTimeout(function () {
        if (olddeporder < 1) {
            $("div#clientdep.fsr input.ui-autocomplete-input").addClass("autoinputerror").removeClass("");
        } else {
            $("div#clientdep.fsr input.ui-autocomplete-input").addClass("").removeClass("autoinputerror");
        }
    }, 1100);


    $(function () {
        $(".normal").autosize();
    });

    $(function () {
        $("#orderselectdep").orderselectdep();
        $("#toggle").click(function () {
            $("#orderselectdep").toggle();
        });
    });

    $(function () {
        $("#combobox").combobox();
        $("#toggle").click(function () {
            $("#combobox").toggle();
        });
    });



    $('#orderaudit').bind('click', function (e) {
        e.preventDefault();
        $.zebra_dialog('', {
            'source': {
                'ajax': ('ajaxaudit.php?orderid=' + id + '&auditpage=order&page')
            },
            'type': 'false',
            'scrolling': 'yes',
            'title': ('Audit log for ' + id)
        });
    });






// collectionworkingwindow
    $(function () {
        $('#collectionworkingwindow').on('blur', function () {
            checkcollectionworkingwindow();
        });
    });




    function cancelpricelock() {
        $.ajax({
            url: 'ajaxchangejob.php', //Server script to process data
            data: {
                page: 'ajaxcancelpricelock',
                formbirthday: formbirthday,
                id: id
            },
            type: 'post',
            success: function (data) {
                $('#emissionsaving').append(data);
            },
            complete: function () {
            resizenewcost();
                showmessage();
            }
        });
    }


    $("#buttoncancelpricelock").click(function () {
        cancelpricelock();
    });



    $('#deleteord').bind('click', function (e) {
        e.preventDefault();
        $.zebra_dialog('<strong>Are you sure ?</strong><br />This Job will be deleted.<br />This action CANNOT be undone.', {
            'type': 'warning',
            'width': '350',
            'title': 'Delete Job ?',
            'buttons': [
                {caption: 'Delete', callback: function () {
                    $("#frmdel").submit();
                }},
                {caption: 'Do NOT Delete', callback: function () {
                    // empty callback does nothing
                }}
            ]
        });
    });



    $("#deleteordmob").bind("click", function (e) {
        e.preventDefault();
        $.zebra_dialog("<strong>Are you sure ?</strong><br />This Job will be deleted. <br />This action CANNOT be undone.", {
            "type": "warning",
            "title": "Delete Job ?",
            "buttons": [
                {caption: "Delete", callback: function () {
                    $("#frmdelmob").submit();
                }},
                {caption: "Do NOT Delete", callback: function () {
                    // empty callback does nothing
                }}
            ]
        });
    });


    ordermapupdater();

}); // ends document ready



function depcomboboxchanged() {
    "use strict";
    var newdeporder = $("select#orderselectdep").val();
    $.ajax({
        url: 'ajaxchangejob.php',
        data: {
            page: 'ajaxchangedep',
            formbirthday: formbirthday,
            id: id,
            newdeporder: newdeporder,
            olddeporder: olddeporder
        },
        type: 'post',
        success: function (data) {
            $('#client').append(data);
        },
        complete: function () {
            showmessage();
        }
    });
}

function comboboxchanged() {
    "use strict";
    var newclientorder = $("select#combobox").val();
    $.ajax({
        url: 'ajaxchangejob.php',
        data: {
            page: 'ajaxchangeclient',
            formbirthday: formbirthday,
            id: id,
            newclientorder: newclientorder,
            oldclientorder: oldclientorder
        },
        type: 'post',
        success: function (data) {
            $('#client').append(data);
        },
        complete: function () {
            showmessage();
        }
    });
}

