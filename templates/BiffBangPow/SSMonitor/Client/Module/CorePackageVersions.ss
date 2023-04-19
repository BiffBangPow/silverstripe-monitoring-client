<section class="bbp-monitor-section">
    <h2>$Title</h2>

<table class="bbp-monitor-results">
<thead>
<tr>
<th><%t BBPMonitoringPackagename 'Package' %></th>
<th><%t BBPMonitoringPackageVersion 'Version' %></th>
</tr>
</thead>
    <tbody>
    <% loop $Packages %>
        <tr>
            <td>$PackageName</td>
            <td>$PackageVersion</td>
        </tr>
    <% end_loop %>
    </tbody>
</table>
</section>
