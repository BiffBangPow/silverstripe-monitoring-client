<section class="bbp-monitor-section">
    <h2>$Title</h2>

<table class="bbp-monitor-results">
<thead>
<tr>
<th><%t BBPMonitoringSSConfVariable 'Variable' %></th>
<th><%t BBPMonitoringSSConfValue 'Value' %></th>
</tr>
</thead>
    <tbody>
    <% loop $Variables %>
        <tr>
            <td>$Variable</td>
            <td>$Value</td>
        </tr>
    <% end_loop %>
    </tbody>
</table>

</section>

