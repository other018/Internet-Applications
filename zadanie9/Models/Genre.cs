using System;
using System.Collections.Generic;
using System.Linq;
using System.Web;
using System.ComponentModel.DataAnnotations;

namespace Pai_MVC.Models
{
    public class Genre
    {
        public int Id { get; set; }
        [Required(ErrorMessage = "Genre name is required!")]
        [StringLength(50, ErrorMessage = "Maximal length of the name of a genre " +
            "is 50 characters!")]
        public string Name { get; set; }
        public ICollection<Song> Songs { get; set; }

    }
}