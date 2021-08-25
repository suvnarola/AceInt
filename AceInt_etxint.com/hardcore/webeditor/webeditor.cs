using System;
using System.Web.UI;
using System.Web.UI.WebControls;

public class webeditor : UserControl {

	public string Rootpath {
		get { return ((ViewState["Rootpath"] == null) ? "/hardcore/webeditor/" : "" + ViewState["Rootpath"]) ; }
		set { ViewState["Rootpath"] = value ; }
	}

	public string Language {
		get { return ((ViewState["Language"] == null) ? "" : "" + ViewState["Language"]) ; }
		set { ViewState["Language"] = value ; }
	}

	public string Name {
		get { return ((ViewState["Name"] == null) ? "content" : "" + ViewState["Name"]) ; }
		set { ViewState["Name"] = value ; }
	}

	public string Value {
		get { return ((ViewState["Value"] == null) ? "" : "" + ViewState["Value"]) ; }
		set { ViewState["Value"] = value ; }
	}

	public string StyleSheet {
		get { return ((ViewState["Stylesheet"] == null) ? "/hardcore/webeditor/hardcore.css" : "" + ViewState["Stylesheet"]) ; }
		set { ViewState["Stylesheet"] = value ; }
	}

	public string Manager {
		get { return ((ViewState["Manager"] == null) ? "" : "" + ViewState["Manager"]) ; }
		set { ViewState["Manager"] = value ; }
	}

	public string onEnter {
		get { return ((ViewState["onEnter"] == null) ? "" : "" + ViewState["onEnter"]) ; }
		set { ViewState["onEnter"] = value ; }
	}

	public string onShiftEnter {
		get { return ((ViewState["onShiftEnter"] == null) ? "" : "" + ViewState["onShiftEnter"]) ; }
		set { ViewState["onShiftEnter"] = value ; }
	}

	public string onCtrlEnter {
		get { return ((ViewState["onCtrlEnter"] == null) ? "" : "" + ViewState["onCtrlEnter"]) ; }
		set { ViewState["onCtrlEnter"] = value ; }
	}

	public string onAltEnter {
		get { return ((ViewState["onAltEnter"] == null) ? "" : "" + ViewState["onAltEnter"]) ; }
		set { ViewState["onAltEnter"] = value ; }
	}

	public string ToolbarFrame {
		get { return ((ViewState["ToolbarFrame"] == null) ? "" : "" + ViewState["ToolbarFrame"]) ; }
		set { ViewState["ToolbarFrame"] = value ; }
	}

	public string Width {
		get { return ((ViewState["Width"] == null) ? "" : "" + ViewState["Width"]) ; }
		set { ViewState["Width"] = value ; }
	}

	public string Height {
		get { return ((ViewState["Height"] == null) ? "" : "" + ViewState["Height"]) ; }
		set { ViewState["Height"] = value ; }
	}

	public string Format {
		get { return ((ViewState["Format"] == null) ? "" : "" + ViewState["Format"]) ; }
		set { ViewState["Format"] = value ; }
	}

	public string Encoding {
		get { return ((ViewState["Encoding"] == null) ? "" : "" + ViewState["Encoding"]) ; }
		set { ViewState["Encoding"] = value ; }
	}

	public string Direction {
		get { return ((ViewState["Direction"] == null) ? "" : "" + ViewState["Direction"]) ; }
		set { ViewState["Direction"] = value ; }
	}

	protected override void OnLoad(EventArgs e)
	{
	}

}
