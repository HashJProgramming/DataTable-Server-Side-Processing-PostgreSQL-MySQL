PGDMP  5    .                |            test_db    16.3    16.3     �           0    0    ENCODING    ENCODING        SET client_encoding = 'UTF8';
                      false            �           0    0 
   STDSTRINGS 
   STDSTRINGS     (   SET standard_conforming_strings = 'on';
                      false            �           0    0 
   SEARCHPATH 
   SEARCHPATH     8   SELECT pg_catalog.set_config('search_path', '', false);
                      false            �           1262    19148    test_db    DATABASE     �   CREATE DATABASE test_db WITH TEMPLATE = template0 ENCODING = 'UTF8' LOCALE_PROVIDER = libc LOCALE = 'English_United States.1252';
    DROP DATABASE test_db;
                postgres    false            �            1259    19155    users    TABLE       CREATE TABLE public.users (
    id integer NOT NULL,
    firstname character varying(255) NOT NULL,
    lastname character varying(255) NOT NULL,
    middlename character varying(255),
    suffix character varying(255),
    phone character varying(20) NOT NULL
);
    DROP TABLE public.users;
       public         heap    postgres    false            �          0    19155    users 
   TABLE DATA           S   COPY public.users (id, firstname, lastname, middlename, suffix, phone) FROM stdin;
    public          postgres    false    215   �       P           2606    19161    users users_pkey 
   CONSTRAINT     N   ALTER TABLE ONLY public.users
    ADD CONSTRAINT users_pkey PRIMARY KEY (id);
 :   ALTER TABLE ONLY public.users DROP CONSTRAINT users_pkey;
       public            postgres    false    215            �   /   x�3��H,����(�O/J����K��/N/J��4@\1z\\\ o/     