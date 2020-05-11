using System;
using System.Collections.Generic;
using System.Linq;
using System.Web;
using System.Web.Mvc;

using Pai_MVC.Models;

namespace Pai_MVC.Controllers
{
    public class FirstSongsController : Controller
    {
        // GET: Songs
        public ActionResult Index()
        {
            Song song = new Song();
            song.Id = 0;
            song.Artist = "Lost Frequencies & Netsky (Bassjackers Remix)";
            song.Name = "Here With You";
            //song.Genre = "Dance/Electronic";

            return View(song);
        }

        public ActionResult SquareRoot(int number)
        {
            String res = (number * number).ToString();
            return Content(res);
        }
    }
}