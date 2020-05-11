using System;
using System.Collections.Generic;
using System.Linq;
using System.Web;
using System.Web.Mvc;
using System.Web.Routing;

namespace Pai_MVC
{
    public class RouteConfig
    {
        public static void RegisterRoutes(RouteCollection routes)
        {
            routes.IgnoreRoute("{resource}.axd/{*pathInfo}");

            /*routes.MapRoute(
                name: "Square1",
                url: "",
                defaults: new { controller = "Songs", action = "SquareRoot", number = 23 }
            );

            routes.MapRoute(
                name: "Square",
                url: "{controller}/Square/{number}",
                defaults: new { controller = "Home", action="SquareRoot", number=23}
            );

            routes.MapRoute(
                name: "Default",
                url: "{controller}/{action}/{id}",
                defaults: new { controller = "Home", action = "Index", id = UrlParameter.Optional }
            );*/

            routes.MapRoute(
                name: "Default",
                url: "{controller}/{action}/{id}",
                defaults: new { controller = "Songs", action = "Index", id = UrlParameter.Optional }
            );

        }
    }
}
