const books = [
    { id: 1, title: "Harry Potter" },
    { id: 2, title: "The Hobbit" },
    { id: 3, title: "Percy Jackson" },
    { id: 4, title: "Indonesiaku"},
    { id: 5, title: "Cinta Bertepuk sebelah Mata"}
];

const bookList = document.getElementById("book-list");
const borrowedList = document.getElementById("borrowed-list");


if (document.getElementById("user-info")) {
    const userName = localStorage.getItem("userName");
    const userClass = localStorage.getItem("userClass");

    if (!userName || !userClass) {
        alert("Silakan isi data terlebih dahulu.");
        window.location.href = "index.html";
    } else {
        document.getElementById("user-info").innerText = `Nama: ${userName} | Kelas: ${userClass}`;
    }
}


function displayBooks() {
    if (!bookList) return;

    bookList.innerHTML = "";
    books.forEach(book => {
        let li = document.createElement("li");
        li.innerHTML = `${book.title} <button onclick="borrowBook(${book.id})">Pinjam</button>`;
        bookList.appendChild(li);
    });
}


function borrowBook(bookId) {
    const userName = localStorage.getItem("userName");
    const userClass = localStorage.getItem("userClass");

    if (!userName || !userClass) {
        alert("Silakan isi data nama dan kelas terlebih dahulu.");
        window.location.href = "index.html";
        return;
    }

    const book = books.find(b => b.id === bookId);
    if (!book) return;

    const confirmBorrow = confirm(`Apakah Anda yakin ingin meminjam buku "${book.title}"?`);
    if (!confirmBorrow) return;

    let li = document.createElement("li");
    li.innerHTML = `${book.title} (Dipinjam oleh: ${userName}, ${userClass}) 
                    <button onclick="returnBook(${book.id})">Kembalikan</button>`;
    borrowedList.appendChild(li);
}

function returnBook(bookId) {
    let borrowedItems = borrowedList.getElementsByTagName("li");
    for (let i = 0; i < borrowedItems.length; i++) {
        if (borrowedItems[i].textContent.includes(books.find(b => b.id === bookId).title)) {
            const confirmReturn = confirm(`Apakah Anda yakin ingin mengembalikan buku ini? "${books.find(b => b.id === bookId).title}"?`);
            if (confirmReturn) {
                borrowedList.removeChild(borrowedItems[i]);
                alert(`Buku "${books.find(b => b.id === bookId).title}" telah dikembalikan.`);
            }
            break;
        }
    }
}


displayBooks();