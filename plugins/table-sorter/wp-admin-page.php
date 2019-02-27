<style>
	ul{list-style:inherit; padding-left:20px}
</style>
<div class="wrap">
	<h1>Table Sorter</h1>
    	<b>Table Sorter</b> turns standard HTML table with THEAD and TBODY tags into a sortable table without page refresh. It works on every table, whether it is coded in the wordpress template(theme) file or generated from the wordpress editor. This plugin is very handy for theme developers. It has many useful features including :
        <ul>
            <li><b>Multi-column sorting :</b> add "tablesorter" class to the required table</li>
            <li><b>Disable sorting from particular column :</b> add "sortless" class to the required TH column within THEAD tag.</li>
            <li><b>Sort multiple columns simultaneously :</b> by holding down the shift key and clicking a second, third or even fourth column header! </li>
            <li><b>Cross-browser support :</b> IE 6.0+, FF 2+, Safari 2.0+, Opera 9.0+</li>
        </ul>
    <h2 class="title">IMPORTANT</h2>
    <ol>
    	<li><b>THEAD</b> and <b>TBODY</b> tags are compulsory in the desired table, otherwise this plugin will not work.</li>
        <li>Add <b><u>tablesorter</u></b> class in the desired <b>TABLE</b> tag.</li>
        <li>For column heading, use <b>TH</b> tag within <b>THEAD</b> tag.</li>
        <li>If you want to exclude a particular column, add <b><u>sortless</u></b> class to that <b>TH</b> tag.</li>
    </ol>
    <h2 class="title">Initial Sorting</h2>
    <p>You can add initial sorting by adding instructions in the CLASS attribute of the HTML table in the format: {sortlist: [[columnIndex, sortDirection], … ]} where columnIndex is a zero-based index for your columns left-to-right and sortDirection is 0 for Ascending and 1 for Descending. A valid argument that sorts ascending first by column 1 and then column 2 looks like: {sortlist: [[0,0],[1,0]]}</p>
    <p><strong>Example : </strong>&lt;table id="myTable" class="tablesorter {sortlist: [[2,0]]}"&gt;</p>
    <h2 class="title">Sort by date</h2>
    <p>To make your date columns sortable, add class <code>dateFormat-dd/mm/yyyy</code> in your Date column head. You can change date format according to your own need.</p>
    <p><b>Example: </b><code>&lt;th class="dateFormat-dd/mm/yyyy"&gt;Date&lt;/th&gt;</code></p>
    <p>All Done. Have fun!</p>
<!--Generating Table HTML to show-->
	<p><strong>For complete documentation and demo, please visit <a href="http://wpreloaded.com/plugins/table-sorter/how-to/" target="_blank">WP Table Sorter</a>  plugin support page. Happy coding!</strong></p>
</div>