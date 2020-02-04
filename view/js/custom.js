jQuery(document).ready(function($){

	var pathname = window.location.pathname;

	//Виклик необхідних функцій для книжок
	if(~pathname.indexOf("books.html")) {

		displayAll();

		function displayAll(){
			$('.listData').remove();
			$.ajax({
				url: "/api/book/read.php",
				success: function(data){
					displayTable('books', data['records'], ['name', 'authors_name', 'publication_name']);
				}
			})
		}

		var publication_select = $('select[name="publication_id"]');
		if(publication_select.length){
			$.ajax({
				url: "/api/publication/read.php",
				success: function(data){
					displaySelect(publication_select, data['records']);
				}
			})	
		};

		var author_select = $('select[name="authors_id[]"]');
		if(author_select.length){
			$.ajax({
				url: "/api/author/read.php",
				success: function(data){
					displaySelect(author_select, data['records']);
				}
			})	
		};

		$(document).on('click', '#books .delete', function(event){
			event.preventDefault();
			var row = $(this).parents('tr');
			$.ajax({
				url: "/api/book/delete.php",
				method: 'POST',
				data: {'id': $(this).data('id')},
				success: function(data){
					$('.message').text(data['message']);
					row.remove();
				}
			})
		})
	}

	//Виклик необхідних функцій для авторів
	if(~pathname.indexOf("authors.html")) {

		displayAll();

		function displayAll(){
			$('.listData').remove();
			$.ajax({
				url: "/api/author/read.php",
				success: function(data){
					displayTable('authors', data['records'], ['name']);
				}
			})
		}
		

		$(document).on('click', '#authors .delete', function(event){
			event.preventDefault();
			var row = $(this).parents('tr');
			$.ajax({
				url: "/api/author/delete.php",
				method: 'POST',
				data: {'id': $(this).data('id')},
				success: function(data){
					$('.message').text(data['message']);
					row.remove();
				},
				error : function(jqXHR, textStatus, errorThrown){
					$('.message').text(jqXHR.responseJSON.message);
				}
			})
		})
	}

	//Виклик необхідних функцій для видавництв
	if(~pathname.indexOf("publications.html")) {

		displayAll();

		function displayAll(){
			$('.listData').remove();
			$.ajax({
				url: "/api/publication/read.php",
				success: function(data){
					displayTable('authors', data['records'], ['name']);
				}
			})
		}
		

		$(document).on('click', '#publications .delete', function(event){
			event.preventDefault();
			var row = $(this).parents('tr');
			$.ajax({
				url: "/api/publication/delete.php",
				method: 'POST',
				data: {'id': $(this).data('id')},
				success: function(data){
					$('.message').text(data['message']);
					row.remove();
				},
				error : function(jqXHR, textStatus, errorThrown){
					$('.message').text(jqXHR.responseJSON.message);
				}
			})
		})
	}

	//Універсальні функції створення, оновлення даних, а також робота з модальним вікном
	$(document).on('submit', 'form.createForm', function(event){
		event.preventDefault();
		$.ajax({
			url: $(this).attr('action'),
			data: $(this).serialize(),
			method: $(this).attr('method'),
			success: function(data){
				displayAll();
				$('form.createForm')[0].reset();
				$('.message').text(data['message']);
			},
			error : function(jqXHR, textStatus, errorThrown){
				$('.message').text(jqXHR.responseJSON.message);
			}
		})
	})

	$(document).on('submit', 'form.updateForm', function(event){
		event.preventDefault();
		$.ajax({
			url: $(this).attr('action'),
			data: $(this).serialize(),
			method: $(this).attr('method'),
			success: function(data){
				displayAll();
				$('.message').text(data['message']);
				$('#updateModal').modal('hide');
			},
			error : function(jqXHR, textStatus, errorThrown){
				$('.messageModal').text(jqXHR.responseJSON.message);
			}
		})	
	})

	$('#updateModal').on('hide.bs.modal', function (event) {
		$(this).find('form.updateForm')[0].reset();
	})

	$(document).on('click', '.update', function(event){
		event.preventDefault();
		$('#updateModal').find('input[name="id"]').attr('value', $(this).data('id'));
		$('#updateModal').modal('show');
	})

})

//Функція для відображення таблиці з даними
function displayTable(tableId, data, output){
	var html = '';
	$.each(data, function(i, item) {
		html += "<tr class='listData'>";
		$.each(output, function(j, item){
			html += "<td>" + data[i][output[j]] + "</td>";
		})
	    html += "<td><a class='delete' data-id='" + data[i].id + "' href='#'>Видалити</a><br><a class='update' data-id='" + data[i].id + "' href='#'>Редагувати</a></td>";
		html += "</tr>";
	});
	$('table tbody').append(html);
}

//Функція для відображення поля селект
function displaySelect(select, data){
	var html = '';
	$.each(data, function(i, item) {
		html += "<option value='" + data[i].id + "'>" + data[i].name + "</option>";
	});
	select.each(function(){
		$(this).append(html);
	})
}