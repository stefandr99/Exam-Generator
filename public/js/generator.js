function checkTest(exercise1, exercise2, exercise3, exercise4) {
    let resultsEx1 = [], resultsEx2 = [], resultsEx3 = [], resultsEx4 = [], i;
    let correctedEx = [];

    for(i = 1; i <= exercise1.options.counter; i++) {
        let id = "ex1option".concat(i.toString());
        resultsEx1.push(document.getElementById(id).checked);
    }

    for(i = 1; i <= exercise2.options.counter; i++) {
        let id = "ex2option".concat(i.toString());
        resultsEx2.push(document.getElementById(id).checked);
    }

    for(i = 1; i <= exercise3.options.counter; i++) {
        let id = "ex3option".concat(i.toString());
        resultsEx3.push(document.getElementById(id).checked);
    }

    for(i = 1; i <= exercise4.options.counter; i++) {
        let id = "ex4option".concat(i.toString());
        resultsEx4.push(document.getElementById(id).checked);
    }

    for(let exercise = 1; exercise <= 4; exercise++) {
        correctedEx[exercise] = [];
        let lg = exercise === 1 ? exercise1.options.counter :
            (exercise === 2 ? exercise2.options.counter :
                (exercise === 3) ? exercise3.options.counter : exercise4.options.counter
            );
        for(i = 1; i <= lg; i++) {
            correctedEx[exercise][i] = (resultsEx1[i] === exercise1.options.solution[i].answer);
        }
    }
}
