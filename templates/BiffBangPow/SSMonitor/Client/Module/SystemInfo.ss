<section class="bbp-monitor-section">
    <h2>$Title</h2>

<table class="bbp-monitor-results">
<thead>
<tr>
<th><%t BBPMonitoringSystemVariable 'Variable' %></th>
<th><%t BBPMonitoringSystemValue 'Value' %></th>
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

<% if $Environment %>

<h2><%t BBPMonitoringEnvVariables 'Environment Variables' %></h2>
<table class="bbp-monitor-results">
<thead>
<tr>
<th><%t BBPMonitoringEnvVariable 'Variable' %></th>
<th><%t BBPMonitoringEnvValue 'Value' %></th>
</tr>
</thead>
    <tbody>
    <% loop $Environment %>
        <tr>
            <td>$Variable</td>
            <td>$Value</td>
        </tr>
    <% end_loop %>
    </tbody>
    </table>
<% end_if %>

</section>

