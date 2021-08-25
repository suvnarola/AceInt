 function new_window(URL) {
  var viewmsg ="toolbar=0,location=0,directories=0,menubar=0,status=1,resizable=0,scrollbars=1,target=_blank,width=546,height=400";
  selectedURL = URL;                
  remotecontrol=window.open(selectedURL, "ViewMessage", viewmsg);
  remotecontrol.focus();
 }
 function new_window2(URL) {
  var sendmsg ="toolbar=0,location=0,directories=0,menubar=0,status=1,resizable=0,scrollbars=1,target=_blank,width=546,height=400";
  selectedURL = URL;                
  remotecontrol=window.open(selectedURL, "SendMessage", sendmsg);
  remotecontrol.focus();
 }
 function alert_me() {
  alert('New Message(s) Waiting');
 }
 