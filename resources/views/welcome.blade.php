<!doctype html>
<html lang="en">
  <head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
  </head>
  <body>
    <div class="container">
        <div class="row d-flex justify-content-end">
            <label id="uploadLabel"for="importInput" class="btns btn btn-primary mx-1 my-1">Feltölt</label>
            <input type="file" accept=".csv"   class="btns btn btn-primary mx-1 my-1" id="importInput"/>    
            <a href="/new" class="btns btn btn-primary mx-1 my-1">Új hozzáadásra</a>
        </div>

        @if (session('deletemsg'))
        <div class="alert alert-dismissible fade show" role="alert">
        <strong>{{ session('deletemsg') }}</strong>
                    
                <table class="table min-size">
                    <thead>
                        <tr>            
                            <th scope="col">ID</th>
                            <th scope="col">Név</th>
                            <th scope="col">E-mail</th>
                        </tr>
                    </thead>
                    <tbody>

                    <tr class="vertical ">
                        <th scope="row">{{session('deletedproduct')->id }}</th>
                        <td>{{session('deletedproduct')->name }}</td>
                        <td>{{session('deletedproduct')->email }}</td>
                    </tr>

                    </tbody>
                </table>
 
            <div class="flex">
                <a class="btn btn-primary "href="/deleteundo/{{session('deletedproduct')}}">
                Visszavonás
                </a>
            </div>
        </div>
                    
    @endif
        <div class="row mx-2 d-flex my-3 justify-content-between">
            <form class="search d-flex py-2" method="get" action="/search">
                <select class="form-select" name="searchBy" aria-label="Default select example">
                    <option value="All" selected>Összes</option>
                    <option value="id">ID</option>
                    <option value="name">Név</option>
                    <option value="email">E-mail</option>
                </select>
                <div>
                    <input type="text" class="form-control" name="search" id="exampleInputEmail1" aria-describedby="emailHelp">
                </div>
                <button type="submit" class="btn btn-primary">Keres</button>
            </form>
            @if (isset($counter))
                <div class="counter">Találatok száma:{{$counter}}</div>
            @endif
            <form class="search formselect d-flex my-2 " action="/select">
                <select class="form-select" name="select" aria-label="Default select example">
                        <option value="10" selected>DB/OLDAL</option>
                        <option value="25">25</option>
                        <option value="50">50</option>
                        <option value="100">100</option>
                </select>
                <button type="submit" class="btn btn-primary">Választ</button>
            </form>
        </div>
        <table class="table min-size">
            <thead>
                <tr>            
                    <th scope="col">ID</th>
                    <th scope="col">Összes <input class="form-check-input" id="all-select" type="checkbox" value="" id="flexCheckChecked"></th>
                    <th scope="col">Név</th>
                    <th scope="col">E-mail</th>
                    <th scope="col" class="text-right">Szerkezt</th>
                </tr>
            </thead>
            <tbody>
                @foreach( $datas as $product)
                    @if ($product->id == session('deletedproductid'))
                        <tr id = "row" class="vertical deleted">
                        <th id = "id"scope="row">{{$product->id}}</th>
                        <td>
                            <input id="checkBox" class="form-check-input" type="checkbox"  value='{{$product->id}}' />
                        </td>
                        <td id = "name">{{$product->name}}</td>
                        <td id = "eamil">{{$product->email}}</td>
                        <td>
                            <div class="d-flex  justify-content-end">
                            <a href="/edit/{{$product->id}}" type="button" class="btn btn-outline-primary mx-1 my-1">Szerkezt</a>
                            <a href="/delete/{{$product->id}}"type="button" class="btn btn-outline-danger mx-1 my-1">Töröl</a>
                            </div>
                        </td>
                        </tr>
                    @else
                        <tr id = "row" class="vertical ">
                            <th id = "id" scope="row">{{$product->id}}</th>
                            <td>
                                <input id="checkBox" class="form-check-input" type="checkbox"  value='{{$product->id}}' />
                            </td>
                            <td id = "name">{{$product->name}}</td>
                            <td id = "email">{{$product->email}}</td>
                            <td>
                                <div class="d-flex  justify-content-end">
                                <a href="/edit/{{$product->id}}" type="button" class="btn btn-outline-primary mx-1 my-1">Szerkezt</a>
                                <a href="/delete/{{$product->id}}"type="button" class="btn btn-outline-danger mx-1 my-1">Töröl</a>
                                </div>
                            </td>
                        </tr>
                    @endif
                @endforeach    
            </tbody>
        </table>

        <nav aria-label="Page navigation example">
            <ul class="pagination justify-content-center ul-d-flex">
                <li class="page-item {{$prevpage==-1 ? 'disabled' : ''}}">
                <a class="page-link" href="/page/{{$prevpage}}" tabindex="-1">Previous</a>
                </li>
                @for( $i=0;$i<$maxpage;$i++)
                 <li class="page-item"><a class="page-link" href="/page/{{$i}}">{{$i}}</a></li>
                @endfor
                <li class="page-item {{$nextpage == $maxpage ? 'disabled' : ''}}">
                <a class="page-link" href="/page/{{$nextpage}}">Next</a>
                </li>
            </ul>
        </nav>

        <div class="row btn-container">
            <div class="all"></div>
            <button type="button" class=" export-btn btn btn-outline-danger mx-1 my-1">Exportál</button>
        </div>
        <table class="table" id="export-table">
            <thead>
                <tr>            
                    <th scope="col">ID</th>
                    <th scope="col">Név</th>
                    <th scope="col">E-mail</th>
                    <th scope="col" class="text-right">Szerkezt</th>
                </tr>
            </thead>
            <tbody class="renderTable"> 
            </tbody>
        </table>
    </div>

    <!-- Optional JavaScript; choose one of the two! -->

    <!-- Option 1: Bootstrap Bundle with Popper -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ka7Sk0Gln4gmtz2MlQnikT1wXgYsOg+OMhuP+IlRH9sENBO0LRn5q+8nbTov4+1p" crossorigin="anonymous"></script>
    <script src="{{ asset('js/app.js') }}"></script>
    <!-- Option 2: Separate Popper and Bootstrap JS -->
    <!--
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.10.2/dist/umd/popper.min.js" integrity="sha384-7+zCNj/IqJ95wo16oMtfsKbZ9ccEh31eOz1HGyDuCQ6wgnyJNSYdrPa03rtR1zdB" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.min.js" integrity="sha384-QJHtvGhmr9XOIpI6YVutG+2QOK9T+ZnN4kzFN1RtK3zEFEIsxhlmWl5/YESvpZ13" crossorigin="anonymous"></script>
    -->
  </body>
</html>