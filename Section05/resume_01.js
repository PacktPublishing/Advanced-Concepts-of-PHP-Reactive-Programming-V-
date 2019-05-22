const Rx = require('rxjs/Rx');
const Observable = Rx.Observable;

Observable.create(observer => {
        observer.next(1);
        observer.error('error message');
        observer.next(3);
    })
    .onErrorResumeNext()
    .subscribe(value => console.log('Next:', value), error => console.log('Error:', error));
