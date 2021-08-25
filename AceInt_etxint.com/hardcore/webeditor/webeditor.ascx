<%@ Control inherits="webeditor" src="webeditor.cs" %>
<script>
<%= Name %> = '<%= Regex.Replace(Regex.Replace(Regex.Replace(Value, "\r", "\r"), "\n", "\n"), "'", "\'") %>'
<%= Name %>_editor = new HardCoreWebEditor('<%= Rootpath %>', '<%= Language %>', '<%= Name %>', <%= Name %>, '', '<%= StyleSheet %>', true, '<%= Manager %>', '<%= onEnter %>', '<%= onShiftEnter %>', '<%= onCtrlEnter %>', '<%= onAltEnter %>', '<%= ToolbarFrame %>', '<%= Width %>', '<%= Height %>', '<%= Format %>', '<%= Encoding %>', '<%= Direction %>');
</script>
