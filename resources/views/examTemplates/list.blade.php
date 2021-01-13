<!-- Aici se primeste $list -->
<h4>
    <div class="px-lg-5">
        <ul class="list-group">
            @for($i = 1; $i <= $list["counter"]; $i++)
                <li>{{$list["items"][$i]}}</li>
            @endfor
        </ul>
    </div>
</h4>
