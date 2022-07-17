const checks = document.querySelectorAll('#checkBox');
const row = document.querySelectorAll('#row');
const all = document.querySelector("#all-select");

let allrecords = undefined;

if(!localStorage.getItem('export')){
    const Store = localStorage.setItem('export',"[]");
}

checks.forEach((element,index )=> {
    element.addEventListener("change", (e) => { inputCheck(e,index)})
});

const deselectbox = () =>{
    const dechecks = document.querySelectorAll(".deselect");
    dechecks.forEach((element,index )=> {
        element.addEventListener("change", (e) => { inputChecked(e,index)})
    });
}

const selectAll = (e) =>{
    if(e.target.checked){
        var xmlhttp = new XMLHttpRequest();
        var url = "/getall";

        xmlhttp.onreadystatechange = function() {
            if (this.readyState == 4 && this.status == 200) {
                var myArr = JSON.parse(this.responseText);
                setAll(myArr);
            }
        };
        xmlhttp.open("GET", url, true);
        xmlhttp.send();
    }else{
        setAll(undefined)
    }
        function setAll(arr) {

            allrecords = arr

            if (allrecords != undefined){
                document.querySelector(".all").innerHTML = "<b>Az összes rekord kiexportálása: "+arr.length+"</b>"
            }else{
                document.querySelector(".all").innerHTML = ""
            }
        }

}

all.addEventListener("change", (e) => {
    selectAll(e)
})

const inputCheck = (e,i) =>{

    row.forEach((el,index )=> {
        if(i == index){
            let Json = {
                "id": el.querySelector("#id").innerHTML,
                "name": el.querySelector("#name").innerHTML,
                "email": el.querySelector("#email").innerHTML
            }
            if(e.target.checked){
                checked(Json)
            }else{
                unchecked(Json)
            }
        }
    });
    deselectbox()
}

const inputChecked = (e,i) =>{
    const table = document.querySelector(".renderTable")
    let indexKey = "";
    table.querySelectorAll(".checked-row").forEach((el,index )=> {
        if(i == index){
            let Json = {
                "id": el.querySelector("#id").innerHTML,
                "name": el.querySelector("#name").innerHTML,
                "email": el.querySelector("#email").innerHTML
            }
            unchecked(Json)
            indexKey = el.querySelector("#id").innerHTML
        }
    });
    deselectbox()

    checks.forEach((element )=> {
        if(element.value == indexKey){
            element.checked = false
        }
    });
}

const unchecked = (el)  => {
    let jsonList = getStore();
    let newList = jsonList.filter(user => user.id != el.id);

    localStorage.setItem('export', JSON.stringify(newList));

    renderTable();
}

const checked = (el) => {
    let jsonList = getStore();
    if(Isexist(el)){
        jsonList.push(el);
    }

    localStorage.setItem('export', JSON.stringify(jsonList));
    renderTable();
}

const getStore = () =>{
    return JSON.parse(localStorage.getItem("export"))
}

const renderTable = () =>{
    let content="";
    let jsonList = getStore();
    const tbody= document.querySelector(".renderTable");
    jsonList.forEach(obj => {
        content +='<tr class="vertical checked-row"><th scope="row" id="id">'+obj.id+'</th><td id="name">'+obj.name+'</td><td id="email">'+obj.email+'</td><td><div class="d-flex  justify-content-end"><div class="form-check"><input class="form-check-input deselect" type="checkbox" value="'+obj.id+'" id="deselect'+obj.id+'" hidden><label class=" btn btn-outline-danger form-check-label" for="deselect'+obj.id+'">Megszüntet</label></div></div></td></tr>'
    });

    tbody.innerHTML = content
}

const Isexist = (e) =>{
    let result=true;
    let jsonList = getStore();
    jsonList.forEach(obj => {
        if(e.id == obj.id){
            result=false;
        }
    });
    return result;

}

const checkdefault= () => {
    let jsonList = getStore();
    jsonList.forEach(obj => {
        checks.forEach((element )=> {
            if(element.value == obj.id){
                element.checked = true
            }
        });
    });
}


checkdefault();
renderTable();
deselectbox();

const exportBtn = document.querySelector(".export-btn");

exportBtn.addEventListener("click", () => { 
    if (allrecords != undefined){
        exportTableToExcel("export-table", allrecords)

    }else{
        exportTableToExcel("export-table", getStore())
    }

})

const exportTableToExcel = (filename, rows) => {
    var processRow = function (row) {
        var finalVal = '';
        finalVal += row.id+","+row.name+","+row.email
        return finalVal + '\n';

    };

    var csvFile = '';
    rows.forEach(obj => {
        csvFile += processRow(obj)
    });

    var blob = new Blob([csvFile], { type: 'text/csv;charset=utf-8;' });
    if (navigator.msSaveBlob) { 
        navigator.msSaveBlob(blob, filename);
    } else {
        var link = document.createElement("a");
        if (link.download !== undefined) { // feature detection
            // Browsers that support HTML5 download attribute
            var url = URL.createObjectURL(blob);
            link.setAttribute("href", url);
            link.setAttribute("download", filename);
            link.style.visibility = 'hidden';
            document.body.appendChild(link);
            link.click();
            document.body.removeChild(link);
        }
    }
}

window.onload = () => {
    let picker = document.getElementById("importInput");
    let label = document.querySelector("#uploadLabel");
    picker.onchange = () => {
      let selected = picker.files[0];

      let reader = new FileReader();
      reader.addEventListener("loadend", () => {
        let data = reader.result.split("\r\n");

        let list = []; 
        for (let i in data) {
            for (let x in data[i].split("\n")){
                list.push(data[i].split("\n")[x].split(",")+"|")
            }
        }
        importTo(list)
        label.innerHTML = '<div class="spinner-border text-light" style="width: 1rem; height: 1rem;" role="status"><span class="sr-only"></span></div>';
      });
      reader.readAsText(selected);
      
    };
};


const importTo = async(x) =>{

    var xmlhttp = new XMLHttpRequest();
    var url = "/import/"+x;
    xmlhttp.onreadystatechange = function() {
        if (this.readyState == 4 && this.status == 200) {
            console.log(this.responseText)
        }
    };
    xmlhttp.open("GET", url, true);
    xmlhttp.send();

    location.reload();

}
