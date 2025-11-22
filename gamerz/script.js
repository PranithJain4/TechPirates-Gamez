let capital = document.getElementById("capital")
let small = document.getElementById("small")
let nums = document.getElementById("nums")
let splc = document.getElementById("splc")
let len = document.getElementById("len");
let pass = document.getElementById("pass")

function capitalCheck(str) {
    return /^[A-Z]+$/.test(str);
}

function smallCheck(str) {
    return /^[a-z]+$/.test(str);
}

function numberCheck(str) {
    return /^[0-9]+$/.test(str);
}

function SpecialCharsCheck(str) {
    return /^[^A-Za-z0-9]+$/.test(str);
}

function EmailFormatCheck(str) {
    return /^[\w\-\.]+@([\w-]+\.)+[\w-]{2,4}$/.test(str);
}


let s = 0, n = 0, spl = 0, l = 0, cap = 0;
pass.addEventListener("input", () => {
    for (let i = 0; i < pass.value.length; i++) {
        let str = pass.value[i]
        // console.log(pass.value[pass.value.length - 1])
        if (capitalCheck(str)) {
            cap = 1
            capital.innerHTML = "✔️";
        }
        if (numberCheck(str)) {
            n = 1;

            nums.innerHTML = "✔️";
        }
        if (smallCheck(str)) {
            s = 1

            small.innerHTML = "✔️";
        }
        if (SpecialCharsCheck(str)) {
            spl = 1

            splc.innerHTML = "✔️";
        }
        if (pass.value.length >= 8) {
            l = 1
            len.innerHTML = "✔️"
        }
        if (pass.value.length < 8) {
            l = 0
            len.innerHTML = "◯"
        }


        if (l == 1 && spl == 1 && s == 1 && n == 1 && cap == 1) {
            {

                subbtn.disabled = false;
                subbtn.style.background = "#00c4b0ff"

            }

        }
        else {
            subbtn.disabled = true;
            subbtn.style.background = "#ddebe9ff"


        }
    }
})